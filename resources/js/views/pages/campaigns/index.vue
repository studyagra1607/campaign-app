<template>
    <div class="main-box">

		<Dialog :header="'Edit Name'" v-model:visible="displaySaveCampaign" :draggable="false" modal :style="{ width: '50vw' }">
			<SaveCampaign />
		</Dialog>
		
		<Dialog :header="'Campaign Name'" v-model:visible="displayViewCampaign" :draggable="false" modal :style="{ width: '50vw' }">
			<ViewCampaign />
		</Dialog>
		
		<TopBar>
			<h4 class="font-semibold text-lg">
				Campaigns
			</h4>
			<Button icon="pi pi-plus" class="btn-outline" iconClass="text-sm font-black" label="Create Campaign" @click="saveCampaignFn()" />
		</TopBar>

		<div class="content-box">
			<div class="grow flex flex-col border mb-2">
				<div class="h-px grow overflow-auto scroll_bar">
					<DataTable
						class="p-datatable-gridlines"
						:value="campaigns"
						dataKey="id"
						:rowHover="true"
						:loading="loading"
						responsiveLayout="scroll"
					>
						<!-- <template #header>
							<div class="search-filter-box" style="--sf-width: 25%; --sf-gap: 1rem;">
								
							</div>
						</template> -->

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

						<Column header="Status" class="w-[20%]">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data }" v-else>
								<Tag severity="success" value="Published" class="text-xs"></Tag>
							</template>
						</Column>

						<Column header="Actions" class="w-[20%]">
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
				</div>
			</div>
			<Paginator :rows="10" :totalRecords="120" :rowsPerPageOptions="[10, 20, 30]"></Paginator>
		</div>
		
	</div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";
import SaveCampaign from "./save.vue";
import ViewCampaign from "./view.vue";

const toast = useToast();
const confirm = useConfirm();

const isSkeleton = ref(false);
const loading = ref(false);

const displaySaveCampaign = ref(false);
const displayViewCampaign = ref(false);

const campaigns = ref([
	{
		id: 1
	},
]);

const saveCampaignFn = (id) => {
	displaySaveCampaign.value = true;
};

const viewCampaignFn = (id) => {
	displayViewCampaign.value = true;
};

const deleteCampaignFn = (id) => {
    confirm.require({
        accept: () => {
			toast.add({ severity: 'warn', summary: 'Deleted successfully!', life: 3000 });
        },
    });
};
</script>

<style scoped>

</style>