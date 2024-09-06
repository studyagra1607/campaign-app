<template>
    <div class="main-box">
		
		<TopBar>
			<h4 class="font-semibold text-lg">
				Notifications
			</h4>
		</TopBar>

		<div class="content-box">
			<div class="content-box-inner">
				<div class="flex flex-col gap-2">
					<div v-for="(data, index) in notifications" :key="index" class="notifications">
						<span>
							Happy Birthday campaign finished successfully!
						</span>
						<Button icon="pi pi-trash" @click="deleteNotificationFn(data.id)" />
					</div>
				</div>
			</div>
		</div>
		
	</div>
</template>

<script setup>
import { ref, onMounted } from "vue";

const notifications = ref([
	{
		id: 1,
	},
	{
		id: 1,
	},
	{
		id: 1,
	},
	{
		id: 1,
	},
]);

const deleteNotificationFn = (id) => {

};
</script>

<style>

</style>