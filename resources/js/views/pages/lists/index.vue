<template>
    <div class="main-box">

		<Dialog :header="'Add List'" v-model:visible="displaySaveList" :draggable="false" modal :style="{ width: '25vw' }">
			<SaveList @closeModal="hideHandler(), filterData()" />
		</Dialog>
		
		<Dialog :header="'List Name'" v-model:visible="displayViewList" :draggable="false" modal :style="{ width: '60vw', height: '80vh' }">
			<ViewList @closeModal="hideHandler(), filterData()" />
		</Dialog>
		
		<TopBar>
			<h4 class="font-semibold text-lg">
				Lists
			</h4>
			<Button icon="pi pi-plus" class="btn-outline" iconClass="text-sm font-black" label="Add List" @click="saveListFn()" />
		</TopBar>

		<div class="content-box">
			<div class="grow flex flex-col border mb-2">
				<div class="h-px grow overflow-auto scroll_bar">
					<DataTable
						class="p-datatable-gridlines"
						:value="lists"
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

						<Column header="Name">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data }" v-else>
								Happy Birthday Dear!
							</template>
						</Column>

						<Column header="Count" class="w-[20%]">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data }" v-else>
								100
							</template>
						</Column>

						<Column header="Actions" class="w-[20%]">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data }" v-else>
								<div class="inline-flex flex-wrap gap-2">
									<Button icon="pi pi-eye" class="btn-squre" @click="viewListFn(data.id)" />
									<Button icon="pi pi-pen-to-square" class="btn-squre" @click="saveListFn(data.id)" />
									<Button icon="pi pi-trash" class="btn-squre" @click="deleteListFn(data.id)" />
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
import { ref } from "vue";
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";
import SaveList from "./save.vue";
import ViewList from "./view.vue";

const toast = useToast();
const confirm = useConfirm();

const isSkeleton = ref(false);
const loading = ref(false);

const displaySaveList = ref(false);
const displayViewList = ref(false);

const lists = ref([
	{
		id: 1
	},
]);

const saveListFn = (id) => {
	displaySaveList.value = true;
};

const viewListFn = (id) => {
	displayViewList.value = true;
};

const deleteListFn = (id) => {
    confirm.require({
        accept: () => {
			toast.add({ severity: 'contrast', summary: 'Deleted successfully!', life: 3000 });
        },
    });
};

const filterData = async () => {
	isSkeleton.value = true;
	isSkeleton.value = false;
};

const hideHandler = async () => {
	displaySaveList.value = false;
	displayViewList.value = false;
};
</script>

<style>

</style>