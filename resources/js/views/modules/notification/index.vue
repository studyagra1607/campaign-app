<template>
    <div class="main-box">
		
		<TopBar>
			<div class="flex flex-wrap gap-2">
				<Button icon="pi pi-trash" class="btn-outline" :label="`Delete (${selectedNotifications?.length})`" :loading="loading" @click="deleteNotificationFn()" v-if="selectedNotifications.length > 0" />
				<Button icon="pi pi-check-square" iconClass="text-sm font-black" label="Select All" @click="selectAllNotificationFn()" v-if="selectedNotifications.length > 0" />
			</div>
		</TopBar>

		<div class="content-box">
			<template v-if="isSkeleton && notifications.length <= 0">
				<div class="content-box-inner flex items-center justify-center">
					<div class="custom-loader"></div>
				</div>
			</template>
			<template v-else-if="notifications?.length > 0">
				<div class="content-box-inner">
					<div class="flex flex-col gap-2">
						<template v-for="(data, index) in notifications" :key="index">
							<div class="notifications" :class="msgTheam['default'], data.read_at ? msgTheam[data.data.type+'-read'] : msgTheam[data.data.type]" @click="updateNotificationFn(data, index)">
								<Checkbox class="checkbox" v-model="selectedNotifications" name="notification" :value="data.id" />
								<span class="date">{{ $format(data.created_at, 'dd-MMM-yyyy, hh:mm aaa') }}</span>
								<span class="msg" v-html="data.data.msg"></span>
							</div>
						</template>
					</div>
				</div>
			</template>
			<div class="content-box-inner" v-else>
				<div class="no-record">
					<img :src="$env?.VITE_APP_URL+'/assets/img/no-record.png'" alt="no-record" class="-mt-12">
					<small class="font-medium text-[.98rem] text-gray-500 mt-3">No Record Found</small>
				</div>
			</div>
		</div>
		
	</div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import useNotification from "@/services/NotificationService";

const { notification, notifications, getNotifications, updateNotification, deleteNotification } = useNotification();

const isSkeleton = ref(false);
const loading = ref(false);

const selectedNotifications = ref([]);

const msgTheam = {
	'default': `rounded-md border`,
	
	'success': `border-green-200 bg-green-100 text-green-600`,
	'error': `border-red-200 bg-red-100 text-red-600`,
	'info': `border-sky-200 bg-sky-200 text-sky-600`,
	'warn': `border-yellow-200 bg-yellow-100 text-yellow-600`,

	'success-read': `border-green-200 bg-green-50 text-green-600`,
	'error-read': `border-red-200 bg-red-50 text-red-600`,
	'info-read': `border-sky-200 bg-sky-100 text-sky-600`,
	'warn-read': `border-yellow-200 bg-yellow-50 text-yellow-600`,
};

onMounted(async () => {
	isSkeleton.value = true;
	await getNotifications();
	isSkeleton.value = false;
});


const updateNotificationFn = async (data, index) => {
	if(!data.read_at){
		await updateNotification(data.id);
		notifications.value[index] = notification.value;
	};
};
	
const deleteNotificationFn = async (id) => {
	await deleteNotification(id ?? selectedNotifications.value);
	await closeModal();
	// notifications.value.splice(index, 1);
};

const selectAllNotificationFn = async () => {
	selectedNotifications.value = getMapData(notifications.value);
};

const filterData = async () => {
	isSkeleton.value = true;
	selectedNotifications.value = [];
	await getNotifications();
	isSkeleton.value = false;
};

const hideHandler = async () => {

};

const closeModal = async () => {
	await hideHandler();
	await filterData();
};

const getMapData = (data) => {
	let res = data?.length > 0 ? data.map((d) => d.id) : null;
	return res;
};

const pusher = Echo.connector.pusher;
const channel = pusher.subscribe(`user.${$userId}`);
channel.bind_global(async (event, data) => {
    await getNotifications();
});

</script>

<style>
.notifications{
    @apply
	relative
    flex
    items-center
	px-3
	py-2
    transition-all
}
.notifications .checkbox{
	@apply
	me-3
}
.notifications span.date{
    @apply
	absolute
	top-1
	right-3
	inline-block
	font-medium
	text-[.6rem]
}
.notifications span.msg{
    @apply
	block 
	text-sm
}
.notifications span.msg a{
	@apply
	text-[.82rem]
	underline
}
.notifications span.msg b{
	@apply
	font-semibold
}
.notifications span.msg ul{
	list-style: disc;
	list-style-position: inside;
	font-size: .72rem;
	padding-left: 1px;
	margin-top: .15rem;
}
.notifications span.msg ul li{
	line-height: 1.5;
}
.notifications > button{
    opacity: 0;
    transform: scale(0, 0);
    transition: .2s;
	margin-left: auto;
	/* @apply
	border-red-300
	bg-red-200
	text-red-700
	!important */
}
.notifications:hover > button{
    opacity: 1;
    transform: scale(1, 1);
}
</style>
