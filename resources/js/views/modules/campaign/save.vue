<template>
    <div class="content-box-inner">
		<form @submit.prevent="saveCampaignFn()">
			<div class="flex gap-5">
				<div class="w-[50%]">
					<div class="flex flex-col gap-2 mb-4">
						<label for="title" class="block font-medium text-sm text-gray-700">
							Enter Title
						</label>
						<InputText id="title" v-model="campaign.name" placeholder="Enter Title" />
						<span class="input-error-msg" v-if="errors?.name">
							{{ errors.name[0] }}
						</span>
					</div>
					<div class="flex flex-col gap-2 mb-4">
						<label for="subject" class="block font-medium text-sm text-gray-700">
							Enter Subject
						</label>
						<InputText id="subject" v-model="campaign.subject" placeholder="Enter Subject" class="cursor-not-allowed" disabled />
					</div>
					<div class="flex flex-col gap-2 mb-4">
						<label for="email" class="block font-medium text-sm text-gray-700">
							Enter Email
						</label>
						<InputText id="email" v-model="campaign.email" placeholder="Enter Email" class="cursor-not-allowed" disabled />
					</div>
					<div class="flex flex-col gap-2 mb-4">
						<label for="time" class="block font-medium text-sm text-gray-700">
							Schedule Time
						</label>
						<DatePicker id="time" v-model="campaign.time" placeholder="Schedule Time" fluid showTime hourFormat="12" dateFormat="dd-M-yy," inputClass="cursor-not-allowed" disabled />
						<div class="flex flex-wrap gap-4 mt-2 mb-3">
							<label class="flex items-center gap-2 text-sm cursor-not-allowed">
								<RadioButton disabled v-model="campaign.timetype" name="timetype" value="always" /> Always
							</label>
							<label class="flex items-center gap-2 text-sm cursor-not-allowed">
								<RadioButton disabled v-model="campaign.timetype" name="timetype" value="once" /> Once
							</label>
							<label class="flex items-center gap-2 text-sm cursor-not-allowed">
								<RadioButton disabled v-model="campaign.timetype" name="timetype" value="count" />
								<div class="inline-flex items-center gap-2">
									<!-- Count: <InputText v-model="campaign.timetypecount" :min="0" :max="99" class="max-w-[5.2rem] text-[.85rem] px-[.5rem] py-1"/> -->
									<InputNumber class="countInpunt pointer-events-none" v-model="campaign.timetypecount" showButtons buttonLayout="horizontal" :min="0" :max="99" disabled>
										<template #incrementbuttonicon>
											<span class="pi pi-plus" />
										</template>
										<template #decrementbuttonicon>
											<span class="pi pi-minus" />
										</template>
									</InputNumber>
								</div>
							</label>
						</div>
					</div>
					<div class="mb-4">
						<Button type="submit" :label="!campaignId ? 'Add Campaign' : 'Edit Campaign'" class="min-w-28" :loading="loading" :disabled="loading" />
					</div>
				</div>
				<div class="w-[50%] flex flex-col">
					<div class="flex flex-col gap-2 mb-4">
						<label for="category" class="block font-medium text-sm text-gray-700">
							Select Category
						</label>
						<Select id="category" v-model="campaign.category_id" :options="all_categories" :filter="true" optionValue="id" optionLabel="name" placeholder="Select Category" />
						<span class="input-error-msg" v-if="errors?.category_id">
							{{ errors.category_id[0] }}
						</span>
					</div>
					<div class="flex flex-col gap-2 mb-4">
						<label for="template" class="block font-medium text-sm text-gray-700">
							Select Template
						</label>
						<Select id="template" v-model="campaign.template_id" :options="all_templates" :filter="true" optionValue="id" optionLabel="name" placeholder="Select Template" @change="selectTemplateFn()" />
						<span class="input-error-msg" v-if="errors?.category_id">
							{{ errors.category_id[0] }}
						</span>
					</div>
					<div class="grow w-full rounded-md bg-gray-100 p-3">
						<iframe :src="$env.VITE_APP_URL+'/file/'+template.hash" frameborder="0" scrolling="no" class="w-full h-full rounded-md" onload="iframeObjectFit(this)" v-if="template.hash"></iframe>
					</div>
				</div>
			</div>
		</form>
	</div>
</template>

<script setup>
import { ref, onMounted, defineProps } from "vue";
import useCategory from "@/services/CategoryService";
import useTemplate from "@/services/TemplateService";
import useCampaign from "@/services/CampaignService";

const props = defineProps({
    campaignId: Number
});

const { all_categories, getAllCategories } = useCategory();
const { template, getTemplate, all_templates, getAllTemplates } = useTemplate();
const { errors, campaign, getCampaign, storeCampaign, updateCampaign } = useCampaign();

const loading = ref(false);

campaign.value = {
	status: true,
};

onMounted(async () => {
	loading.value = true;
	await getAllCategories();
	await getAllTemplates();
	if(props.campaignId){
		await getCampaign(props.campaignId);
		await getTemplate(campaign.value.template_id);
	};
	loading.value = false;
});

const saveCampaignFn = async () => {
	loading.value = true;
	if(!props.campaignId){
		await storeCampaign(campaign.value);
	}else{
		await updateCampaign(props.campaignId, campaign.value);
	};
	loading.value = false;
};

const selectTemplateFn = async () => {
	loading.value = true;
	await getTemplate(campaign.value.template_id);
	loading.value = false;
};
</script>

<style>
.countInpunt input{
	text-align: center;
	max-width: 2.4rem;
	padding: .25rem .5rem;
}
.countInpunt input:hover,
.countInpunt input:focus{
	outline: #d1d5db !important;
	border-color: #d1d5db !important;
}
.countInpunt button{
	width: 1.5rem;
	padding: 0;
}
.countInpunt button span{
	font-size: .6rem !important;
}
</style>