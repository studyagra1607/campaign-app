<template>
    <div class="main-box">

		<Dialog :header="!categoryId ? 'Add Category' : 'Edit Category'" v-model:visible="displaySaveCategory" :draggable="false" modal :style="{ width: '25vw' }">
			<SaveCategory :categoryId="categoryId" @closeModal="hideHandler(), filterData()" />
		</Dialog>
		
		<TopBar>
			<Button icon="pi pi-plus" class="btn-outline" iconClass="text-sm font-black" label="Add Category" @click="saveCategoryFn()" />
		</TopBar>

		<div class="content-box">
			<template v-if="false">
				<TableWrapper>
					<DataTable
					class="p-datatable-gridlines"
					dataKey="id"
					:rowHover="true"
					:value="categories.data"
					:pt="{
						tableContainer: 'scroll_bar_none'
					}"
					>
						<template #empty>
							<div class="text-center">
								No records found.
							</div>
						</template>

						<Column header="Name">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data }" v-else>
								{{ data.name }}
							</template>
						</Column>

						<Column header="Actions" class="w-[25%]">
							<template #body v-if="isSkeleton">
								<div class="flex">
									<Skeleton size="2rem" class="mr-2"></Skeleton>
									<Skeleton size="2rem" class="mr-2"></Skeleton>
								</div>
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
import SaveCategory from "./save.vue";

const confirm = useConfirm();

const { errors, categories, getCategories, deleteCategory } = useCategory();

const isSkeleton = ref(false);
const loading = ref(false);

const categoryId = ref(null);

const displaySaveCategory = ref(false);

onMounted(async () => {
	isSkeleton.value = true;
	await getCategories();
	isSkeleton.value = false;
});

const saveCategoryFn = (id) => {
	displaySaveCategory.value = true;
	categoryId.value = id;
};

const deleteCategoryFn = (id) => {
    confirm.require({
        accept: async () => {
			await deleteCategory(id);
			await closeModal();
        },
    });
};

const filterData = async () => {
	isSkeleton.value = true;
	await getCategories();
	isSkeleton.value = false;
};

const hideHandler = async () => {
	displaySaveCategory.value = false;
};

const closeModal = async () => {
	await hideHandler();
	await filterData();
};
</script>

<style>

</style>