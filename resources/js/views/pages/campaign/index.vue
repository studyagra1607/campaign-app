<template>
    <div class="main-box">

		<Dialog :header="!campaignId ? 'Add Campaign' : 'Edit Campaign'" v-model:visible="displaySaveCampaign" :draggable="false" modal :style="{ width: '50vw' }">
			<SaveCampaign :campaignId="campaignId" />
		</Dialog>
		
		<Dialog :header="'Campaign Name'" v-model:visible="displayViewCampaign" :draggable="false" modal :style="{ width: '50vw' }">
			<ViewCampaign />
		</Dialog>
		
		<TopBar :items="breadcrumbs">
			<Button icon="pi pi-plus" class="btn-outline" iconClass="text-sm font-black" label="Create Campaign" @click="saveCampaignFn(null)" />
		</TopBar>

		<div class="content-box">
			<TableWrapper>
				<DataTable
				tableClass="min-w-[1000px]"
				class="p-datatable-gridlines"
				dataKey="id"
				:rowHover="true"
				:loading="loading"
				:value="campaigns"
				:pt="{
					tableContainer: 'scroll_bar_none'
				}"
				>

					<template #empty>
						<div class="text-center">
							No records found.
						</div>
					</template>

					<template #loading>
						<div class="text-center">
							Loading data. Please wait.
						</div>
					</template>

					<Column header="Title">
						<template #body v-if="isSkeleton">
							<Skeleton></Skeleton>
						</template>
						<template #body="{ data }" v-else>
							Happy Birthday Dear!
						</template>
					</Column>

					<Column header="Status">
						<template #body v-if="isSkeleton">
							<Skeleton></Skeleton>
						</template>
						<template #body="{ data }" v-else>
							<Tag severity="success" value="Published" class="text-xs"></Tag>
						</template>
					</Column>
					
					<Column header="Date">
						<template #body v-if="isSkeleton">
							<Skeleton></Skeleton>
						</template>
						<template #body="{ data }" v-else>
							<Tag severity="success" value="Published" class="text-xs"></Tag>
						</template>
					</Column>

					<Column header="Active">
						<template #body v-if="isSkeleton">
							<Skeleton></Skeleton>
						</template>
						<template #body="{ data }" v-else>
							<Tag severity="success" value="Published" class="text-xs"></Tag>
						</template>
					</Column>

					<Column header="Actions">
						<template #body v-if="isSkeleton">
							<Skeleton></Skeleton>
						</template>
						<template #body="{ data }" v-else>
							<div class="inline-flex flex-wrap gap-2">
								<Button icon="pi pi-eye" class="btn-squre" @click="viewCampaignFn(data.id)" />
								<Button icon="pi pi-pen-to-square" class="btn-squre" @click="saveCampaignFn(data.id)" />
								<Button icon="pi pi-trash" class="btn-squre" @click="deleteCampaignFn(data.id)" />
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
import useCampaign from "@/services/CampaignService";
import SaveCampaign from "./save.vue";
import ViewCampaign from "./view.vue";

const confirm = useConfirm();
const { errors, campaigns } = useCampaign();

const isSkeleton = ref(false);
const loading = ref(false);

const displaySaveCampaign = ref(false);
const displayViewCampaign = ref(false);

const campaignId = ref(false);

const breadcrumbs = ref([
	{
		label: "Campaigns",
		route: "/emails",
	},
]);

onMounted(async () => {
	campaigns.value = [
		{
			id: 1
		},
	];
});

const saveCampaignFn = (id) => {
	campaignId.value = id;
	displaySaveCampaign.value = true;
};

const viewCampaignFn = (id) => {
	displayViewCampaign.value = true;
};

const deleteCampaignFn = (id) => {
    confirm.require({
        accept: () => {
			staticToast({msg: "Deleted successfully!", severity: 'contrast'});
        },
    });
};
</script>

<style scoped>

</style>