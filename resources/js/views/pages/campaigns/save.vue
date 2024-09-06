<template>
    <div class="content-box-inner">
		<form @submit.prevent="saveCampaignFn()">
			<div class="flex gap-5">
				<div class="w-[50%]">
					<div class="flex flex-col gap-2 mb-4">
						<label for="title" class="block font-medium text-sm text-gray-700">
							Enter Title
						</label>
						<InputText id="title" v-model="campaign.title" placeholder="Enter Title" />
					</div>
					<div class="flex flex-col gap-2 mb-4">
						<label for="subject" class="block font-medium text-sm text-gray-700">
							Enter Subject
						</label>
						<InputText id="subject" v-model="campaign.subject" placeholder="Enter Subject" disabled />
					</div>
					<div class="flex flex-col gap-2 mb-4">
						<label for="email" class="block font-medium text-sm text-gray-700">
							Enter Email
						</label>
						<InputText id="email" v-model="campaign.email" placeholder="Enter Email" disabled />
					</div>
					<div class="flex flex-col gap-2 mb-4">
						<label for="time" class="block font-medium text-sm text-gray-700">
							Schedule Time
						</label>
						<DatePicker id="time" v-model="campaign.time" placeholder="Schedule Time" fluid showTime hourFormat="12" dateFormat="dd-M-yy," disabled />
					</div>
					<div class="mb-4">
						<Button type="submit" :loading="loading" label="Create" class="min-w-28" />
					</div>
				</div>
				<div class="w-[50%] flex flex-col">
					<div class="flex flex-col gap-2 mb-4">
						<label for="list" class="block font-medium text-sm text-gray-700">
							Select List
						</label>
						<Dropdown id="list" v-model="campaign.list" :options="lists" :filter="true" optionValue="id" optionLabel="value" placeholder="Select List" />
					</div>
					<div class="flex flex-col gap-2 mb-4">
						<label for="template" class="block font-medium text-sm text-gray-700">
							Select Template
						</label>
						<Dropdown id="template" v-model="campaign.template" :options="tempates" :filter="true" optionValue="id" optionLabel="value" placeholder="Select Template" />
					</div>
					<div class="grow w-full rounded-md bg-gray-100 p-3">
						<iframe :src="campaign.file" frameborder="0" scrolling="no" class="w-full h-full rounded-md"></iframe>
					</div>
				</div>
			</div>
		</form>
	</div>
</template>

<script setup>
import { ref, onMounted } from "vue";

const loading = ref(false);

const lists = ref([]);
const tempates = ref([]);
const campaign = ref({});

const saveCampaignFn = async () => {
	loading.value = true;
	console.log(campaign.value);
	loading.value = false;
};
</script>

<style>

</style>