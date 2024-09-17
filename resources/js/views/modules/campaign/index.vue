<template>
    <div class="main-box">

		<Dialog :header="!campaignId ? 'Add Campaign' : 'Edit Campaign'" v-model:visible="displaySaveCampaign" :draggable="false" modal style="width: 798px; max-width: 90vw;">
			<SaveCampaign :campaignId="campaignId" @closeModal="hideHandler(), filterData()" />
		</Dialog>
		
		<Dialog :header="'Campaign Name'" v-model:visible="displayViewCampaign" :draggable="false" modal style="width: 742px; max-width: 90vw;">
			<ViewCampaign :campaignId="campaignId" @closeModal="hideHandler(), filterData()" />
		</Dialog>
		
		<TopBar>
			<div class="flex flex-wrap gap-2">
				<Button icon="pi pi-trash" class="btn-outline" :label="`Delete (${selectedCampaigns?.length})`" :loading="loading" @click="deleteCampaignFn()" v-if="selectedCampaigns?.length > 0" 
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
				<Button icon="pi pi-plus" class="btn-outline" iconClass="text-sm font-black" label="Create Campaign" @click="saveCampaignFn()" />
			</div>
		</TopBar>

		<div class="content-box">
			<template v-if="isSkeleton && campaigns.length <= 0">
				<div class="content-box-inner flex items-center justify-center">
					<div class="custom-loader"></div>
				</div>
			</template>
			<template v-else-if="campaigns?.data?.length > 0">
				<TableWrapper>
					<DataTable
					v-model:selection="selectedCampaigns"
					tableClass="min-w-[1400px]"
					class="p-datatable-gridlines"
					editMode="cell"
					dataKey="id"
					:rowHover="false"
					:value="campaigns.data"
					:pt="{
						tableContainer: 'scroll_bar_none'
					}"
					>

						<Column selectionMode="multiple" headerStyle="width: 3rem">

						</Column>
						
						<Column header="Template" class="p-1">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data, index }" v-else>
								<iframe :src="$env.VITE_APP_URL+'/file/'+data.template?.hash" frameborder="0" scrolling="no" cross-origin="anonymous" onload="iframeObjectFit(this, 600)" class="w-[60px] h-[60px] bg-gray-50"></iframe>
							</template>
						</Column>
						
						<Column header="Campaign" class="w-[16%]">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data, index }" v-else>
								{{ data.name }}
							</template>
						</Column>

						<Column header="Category" class="w-[16%]">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data, index }" v-else>
								{{ data.category.name }}
							</template>
							<template #editor="{ data, index }">
								<Select id="category" v-model="data.category_id" :options="all_categories" :filter="true" optionValue="id" optionLabel="name" placeholder="Select Category" class="w-full" @change="changeCategoryFn(data, index)" />
							</template>
						</Column>
						
						<Column header="Date & Time" class="w-[14%]">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data, index }" v-else>
								<template v-if="new Date(`${data.last_run}`) >= new Date()">
									<Tag severity="danger" :value="$format(data.last_run, 'dd-MMM-yyyy, hh:mm a')" class="text-xs"></Tag>
								</template>
								<template v-else-if="new Date(`${data.last_run}`) < new Date()">
									<Tag severity="warn" :value="$format(data.last_run, 'dd-MMM-yyyy, hh:mm a')" class="text-xs"></Tag>
								</template>
								<template v-else>
									-
								</template>
							</template>
						</Column>

						<Column header="Status" class="w-[10%]">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data, index }" v-else>
								<Tag value="Draft" class="text-xs" v-if="data.progress_status == 'draft'"></Tag>
								<Tag severity="warn" value="Running" class="text-xs" v-if="data.progress_status == 'running'"></Tag>
								<Tag severity="info" value="Schedule" class="text-xs" v-if="data.progress_status == 'schedule'"></Tag>
								<Tag severity="success" value="Complete" class="text-xs" v-if="data.progress_status == 'complete'"></Tag>
							</template>
						</Column>

						<Column header="Run Count" class="w-[10%]">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data, index }" v-else>
								{{ data.run_count ?? 0 }}
							</template>
						</Column>
						
						<Column header="Active" class="w-[8%]">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data, index }" v-else>
								<ToggleSwitch v-model="data.status" @change="changeStatusFn(data, index)"/>
							</template>
						</Column>

						<Column header="Actions" class="w-[12%]">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data, index }" v-else>
								<div class="inline-flex flex-wrap gap-2">
									<Button icon="pi pi-eye" class="btn-squre" @click="viewCampaignFn(data.id)" />
									<Button icon="pi pi-pen-to-square" class="btn-squre" @click="saveCampaignFn(data.id)" />
									<Button icon="pi pi-trash" class="btn-squre" @click="deleteCampaignFn(data.id)" />
								</div>
							</template>
						</Column>
					</DataTable>
				</TableWrapper>
				<Paginator
					v-model:first="pagination_page"
					@page="getPaginationPage"
					:pageLinkSize="5"
					:rows="pagination_rows"
					:totalRecords="campaigns.total"
					:rowsPerPageOptions="[25, 50, 100]"
					currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
					:template="{
						default: 'CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown'
					}"
					:pt="{
						root: 'bg-gray-50',
						current: 'me-auto',
						content: 'w-full',
					}"
				>
				</Paginator>
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
import { useConfirm } from "primevue/useconfirm";
import useCategory from "@/services/CategoryService";
import useCampaign from "@/services/CampaignService";
import SaveCampaign from "./save.vue";
import ViewCampaign from "./view.vue";

const confirm = useConfirm();
const { all_categories, getAllCategories } = useCategory();
const { campaign, campaigns, getCampaigns, updateCampaign, deleteCampaign } = useCampaign();

const isSkeleton = ref(false);
const loading = ref(false);
const pagination_page = ref(0);
const pagination_rows = ref(25);

const displaySaveCampaign = ref(false);
const displayViewCampaign = ref(false);

const selectedCampaigns = ref([]);
const campaignId = ref(null);

onMounted(async () => {
	isSkeleton.value = true;
	await getAllCategories();
	await getCampaigns();
	isSkeleton.value = false;
});

const saveCampaignFn = (id) => {
	displaySaveCampaign.value = true;
	campaignId.value = id;
};

const viewCampaignFn = (id) => {
	displayViewCampaign.value = true;
	campaignId.value = id;
};

const deleteCampaignFn = (id) => {
    confirm.require({
        accept: async () => {
			await deleteCampaign(id ?? getMapData(selectedCampaigns.value));
			await closeModal();
        },
    });
};

const changeCategoryFn = async (data, index) => {
	await updateCampaign(data.id, data);
	campaigns.value.data[index] = campaign.value;
};

const changeStatusFn = async (data, index) => {
	await updateCampaign(data.id, data);
	campaigns.value.data[index] = campaign.value;
};

const filterData = async () => {
	pagination_page.value = 0;
	isSkeleton.value = true;
	selectedCampaigns.value = [];
	await getCampaigns(pagination_page.value+1, pagination_rows.value);
	isSkeleton.value = false;
};

const hideHandler = async () => {
	displaySaveCampaign.value = false;
	displayViewCampaign.value = false;
};

const closeModal = async () => {
	await hideHandler();
	await filterData();
};

const getMapData = (data) => {
	let res = data?.length > 0 ? data.map((d) => d.id) : null;
	return res;
};

const getPaginationPage = async (page) => {
	isSkeleton.value = true;
	pagination_rows.value = page.rows;
	await getCampaigns((page.page + 1), page.rows);
	isSkeleton.value = false;
}
</script>

<style scoped>

</style>