<template>
    <div class="main-box">

		<Dialog :header="'Emails'" v-model:visible="displayUploadEmail" :draggable="false" modal :style="{ width: '25vw' }">
			<UploadEmail @closeModal="hideHandler(), filterData()" />
		</Dialog>
		
		<!-- <Dialog :header="'Username'" v-model:visible="displaySaveEmail" :draggable="false" modal :style="{ width: '25vw' }">
			<SaveEmail @closeModal="hideHandler(), filterData()" />
		</Dialog> -->
		
		<TopBar>
			<div>
				<Button icon="pi pi-trash" class="me-2" label="Delete" :loading="loading" @click="deleteSelectedEmailsFn()" v-if="selectedEmails.length > 0" 
					v-tooltip.top="{ value: 'Delete selected emails.',
						pt: {
							arrow: {
								class: {
									'!border-y-gray-700': true,
								},
							},
							text: '!bg-gray-700 !text-gray-100'
						}
					}"
				/>
				<Button icon="pi pi-download" class="btn-outline" iconClass="text-sm font-black" label="Import Emails" @click="uploadEmailFn()" />
			</div>
		</TopBar>

		<div class="content-box">
			<TableWrapper>
				<DataTable
				v-model:selection="selectedEmails"
				tableClass="min-w-[1000px]"
				class="p-datatable-gridlines"
				editMode="cell"
				dataKey="id"
				:rowHover="true"
				:loading="loading"
				:value="emails"
				:pt="{
					tableContainer: 'scroll_bar_none'
				}"
				>
					<template #empty>
						No records found.
					</template>

					<template #loading>
						Loading data. Please wait.
					</template>

					<Column selectionMode="multiple" headerStyle="width: 3rem">

					</Column>

					<Column header="Name">
						<template #body v-if="isSkeleton">
							<Skeleton></Skeleton>
						</template>
						<template #body="{ data }" v-else>
							Bharat Kushwaha
						</template>
					</Column>

					<Column header="Email">
						<template #body v-if="isSkeleton">
							<Skeleton></Skeleton>
						</template>
						<template #body="{ data }" v-else>
							bharat@gmail.com
						</template>
					</Column>

					<Column header="Category" class="w-[13rem]">
						<template #body v-if="isSkeleton">
							<Skeleton></Skeleton>
						</template>
						<template #body="{ data }" v-else>
							Category
						</template>
						<template #editor="{ data }">
							<Dropdown id="category" v-model="categoryId" :options="categoryies" :filter="true" optionValue="id" optionLabel="value" placeholder="Select Category" @change="changeCategoryFn(data.id)" />
						</template>
					</Column>
					
					<Column header="Subscribe">
						<template #body v-if="isSkeleton">
							<Skeleton></Skeleton>
						</template>
						<template #body="{ data }" v-else>
							<Tag severity="success" value="Subscribed" class="text-xs"></Tag>
						</template>
					</Column>

					<Column header="Status">
						<template #body v-if="isSkeleton">
							<Skeleton></Skeleton>
						</template>
						<template #body="{ data }" v-else>
							<ToggleSwitch v-model="data.status" @change="changeStatusFn(data.id)"/>
						</template>
					</Column>

					<Column header="Actions">
						<template #body v-if="isSkeleton">
							<Skeleton></Skeleton>
						</template>
						<template #body="{ data }" v-else>
							<div class="inline-flex flex-wrap gap-2">
								<Button icon="pi pi-pen-to-square" class="btn-squre" @click="saveEmailFn(data.id)" disabled />
								<Button icon="pi pi-trash" class="btn-squre" @click="deleteEmailFn(data.id)" />
							</div>
						</template>
					</Column>
				</DataTable>
			</TableWrapper>
			<Paginator :rows="10" :totalRecords="120" :rowsPerPageOptions="[10, 20, 30]"></Paginator>
		</div>
		
	</div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useConfirm } from "primevue/useconfirm";
import UploadEmail from "./upload.vue";
import SaveEmail from "./save.vue";

const confirm = useConfirm();

const isSkeleton = ref(false);
const loading = ref(false);

const selectedEmails = ref([]);
const categoryId = ref(null);

const displayUploadEmail = ref(false);
const displaySaveEmail = ref(false);

const categoryies = ref([
	{
		id: 1,
		value: 'Premium'
	},
]);

const emails = ref([
	{
		id: 1
	},
]);

const uploadEmailFn = () => {
	displayUploadEmail.value = true;
};

const saveEmailFn = (id) => {
	displaySaveEmail.value = true;
};

const deleteEmailFn = (id) => {
    confirm.require({
        accept: () => {
			staticToast({msg: "Deleted successfully!", severity: 'contrast'});
        },
    });
};

const changeCategoryFn = (id) => {
	console.log('id', id);
	console.log('categoryId', categoryId.value);
};

const changeStatusFn = (id) => {
	console.log('id', id);
};

const deleteSelectedEmailsFn = () => {
	console.log("selectedEmails", selectedEmails.value);
};

const filterData = async () => {
	isSkeleton.value = true;
	isSkeleton.value = false;
};

const hideHandler = async () => {
	displaySaveEmail.value = false;
	displayUploadEmail.value = false;
};
</script>

<style>

</style>