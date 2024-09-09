<template>
    <div class="main-box">

		<Dialog :header="'Add Category'" v-model:visible="displaySaveCategory" :draggable="false" modal :style="{ width: '25vw' }">
			<SaveCategory @closeModal="hideHandler(), filterData()" />
		</Dialog>
		
		<TopBar>
			<Button icon="pi pi-plus" class="btn-outline" iconClass="text-sm font-black" label="Add Category" @click="saveCategoryFn()" />
		</TopBar>

		<div class="content-box">
			<TableWrapper>
				<DataTable
				class="p-datatable-gridlines"
				dataKey="id"
				:rowHover="true"
				:loading="loading"
				:value="categories"
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

					<Column header="Name">
						<template #body v-if="isSkeleton">
							<Skeleton></Skeleton>
						</template>
						<template #body="{ data }" v-else>
							Happy Birthday Dear!
						</template>
					</Column>

					<Column header="Actions" class="w-[25%]">
						<template #body v-if="isSkeleton">
							<Skeleton></Skeleton>
						</template>
						<template #body="{ data }" v-else>
							<div class="inline-flex flex-wrap gap-2">
								<Button icon="pi pi-pen-to-square" class="btn-squre" @click="saveCategoryFn(data.id)" />
								<Button icon="pi pi-trash" class="btn-squre" @click="deleteCategoryFn(data.id)" />
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
import { ref } from "vue";
import { useConfirm } from "primevue/useconfirm";
import SaveCategory from "./save.vue";

const confirm = useConfirm();

const isSkeleton = ref(false);
const loading = ref(false);

const displaySaveCategory = ref(false);

const categories = ref([
	{
		id: 1
	},
]);

const saveCategoryFn = (id) => {
	displaySaveCategory.value = true;
};

const deleteCategoryFn = (id) => {
    confirm.require({
        accept: () => {
			staticToast({msg: "Deleted successfully!", severity: 'contrast'});
        },
    });
};

const filterData = async () => {
	isSkeleton.value = true;
	isSkeleton.value = false;
};

const hideHandler = async () => {
	displaySaveCategory.value = false;
};
</script>

<style>

</style>