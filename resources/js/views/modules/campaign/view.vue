<template>
    <div class="content-box-inner">
		<form @submit.prevent="runCampaignFn()">
			<div class="flex gap-5">
				<div class="w-[50%]">
					<div class="flex flex-col gap-2 mb-4">
						<label for="category" class="block font-medium text-sm text-gray-700">
							Selected Category
						</label>
						<InputText type="text" :placeholder="campaign?.category?.name" class="pointer-events-none" readonly />
					</div>
					<div class="flex flex-col gap-2 mb-4">
						<label for="template" class="block font-medium text-sm text-gray-700">
							Selected Template
						</label>
						<InputText type="text" :placeholder="campaign?.template?.name" class="pointer-events-none" readonly />
					</div>
					<div class="flex flex-col gap-2 mb-4">
						<label class="block font-medium text-sm text-gray-700 mb-1">
							Select Users
						</label>
						<div class="flex flex-wrap gap-4 mb-1">
							<label class="flex items-center gap-2 text-sm cursor-not-allowed">
								<RadioButton disabled v-model="campaign.users" name="users" value="all" /> All
							</label>
							<label class="flex items-center gap-2 text-sm cursor-not-allowed">
								<RadioButton disabled v-model="campaign.users" name="users" value="active" /> Actives
							</label>
							<label class="flex items-center gap-2 text-sm cursor-not-allowed">
								<RadioButton disabled v-model="campaign.users" name="users" value="subscriber" /> Subscribers
							</label>
							<!-- <label class="flex items-center gap-2 text-sm cursor-not-allowed">
								<RadioButton v-model="campaign.users" name="new" value="new" /> All
							</label>
							<label class="flex items-center gap-2 text-sm cursor-not-allowed">
								<RadioButton v-model="campaign.users" name="new" value="new" /> Actives
							</label>
							<label class="flex items-center gap-2 text-sm cursor-not-allowed">
								<RadioButton v-model="campaign.users" name="new" value="new" /> Unactives
							</label>
							<label class="flex items-center gap-2 text-sm cursor-not-allowed">
								<RadioButton v-model="campaign.users" name="new" value="new" /> All
							</label>
							<label class="flex items-center gap-2 text-sm cursor-not-allowed">
								<RadioButton v-model="campaign.users" name="new" value="new" /> Subscribers
							</label>
							<label class="flex items-center gap-2 text-sm cursor-not-allowed">
								<RadioButton v-model="campaign.users" name="new" value="new" /> Unubscribers
							</label> -->
						</div>
					</div>
					<div class="mb-4">
						<Button type="submit" icon="pi pi-caret-right text-sm" :loading="loading" label="Run" class="min-w-28" />
					</div>
					<span class="text-[.68rem]">
						Note: defaulte <b>subscribers</b> are selected.
					</span>
				</div>
				<div class="w-[50%] flex">
					<div class="w-full rounded-md bg-gray-100 p-3">
						{{ campaign?.template?.name }}
						<!-- <iframe :src="$env.VITE_APP_URL+'/file/'+campaign?.template?.hash" frameborder="0" scrolling="no" class="w-full h-full rounded-md"></iframe> -->
						<iframe src="" frameborder="0" scrolling="no" class="w-full h-full rounded-md"></iframe>
					</div>
				</div>
			</div>
		</form>
	</div>
</template>

<script setup>
import { ref, defineProps, onMounted } from "vue";
import useCampaign from "@/services/CampaignService";

const props = defineProps({
    campaignId: Number
});

const { errors, campaign, getCampaign } = useCampaign();

const loading = ref(false);

onMounted(async () => {
	loading.value = true;
	if(props.campaignId){
		await getCampaign(props.campaignId);
	};
	loading.value = false;
});

const runCampaignFn = async () => {
	loading.value = true;
	console.log(campaign.value);
	loading.value = false;
};
</script>

<style>

</style>