<template>
    <div class="main-box">

		<Dialog :header="!categoryId ? 'Add Category' : 'Edit Category'" v-model:visible="displaySaveCategory" :draggable="false" modal style="width: 380px; max-width: 90vw;">
			<SaveCategory :categoryId="categoryId" @closeModal="hideHandler(), filterData()" />
		</Dialog>
		
		<TopBar>
			<div class="flex flex-wrap gap-2">
				<Button icon="pi pi-trash" class="btn-outline" :label="`Delete (${selectedCategories?.length})`" :loading="loading" @click="deleteCategoryFn()" v-if="selectedCategories.length > 0" 
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
				<Button icon="pi pi-plus" class="btn-outline" iconClass="text-sm font-black" label="Add Category" @click="saveCategoryFn()" />
			</div>
		</TopBar>

		<div class="content-box">
			<template v-if="categories?.data?.length > 0">
				<TableWrapper>
					<DataTable
					v-model:selection="selectedCategories"
					tableClass="min-w-[800px]"
					class="p-datatable-gridlines"
					editMode="cell"
					dataKey="id"
					:rowHover="false"
					:loading="loading"
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

						<Column selectionMode="multiple" headerStyle="width: 3rem">

						</Column>

						<Column header="Name">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data }" v-else>
								{{ data.name }}
							</template>
						</Column>

						<Column header="Emails" class="w-[14%]">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data }" v-else>
								{{ data.emails_count }}
							</template>
						</Column>

						<Column header="Availables" class="w-[14%]">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data }" v-else>
								{{ data.availables_count }}
							</template>
						</Column>

						<Column header="Status" class="w-[15%]">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data, index }" v-else>
								<ToggleSwitch v-model="data.status" @change="changeStatusFn(data, index)"/>
							</template>
						</Column>

						<Column header="Actions" class="w-[18%]">
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
				<Paginator
					v-model:first="pagination_page"
					@page="getPaginationPage"
					:pageLinkSize="5"
					:rows="25"
					:totalRecords="categories.total"
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
import SaveCategory from "./save.vue";

const confirm = useConfirm();

const { category, categories, getCategories, updateCategory, deleteCategory } = useCategory();

const isSkeleton = ref(false);
const loading = ref(false);
const pagination_page = ref(0);

const selectedCategories = ref([]);
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
			await deleteCategory(id ?? getMapData(selectedCategories.value));
			await closeModal();
        },
    });
};

const changeStatusFn = async (data, index) => {
	await updateCategory(data.id, data);
	categories.value.data[index] = category.value;
};

const filterData = async () => {
	pagination_page.value = 0;
	isSkeleton.value = true;
	selectedCategories.value = [];
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

const getMapData = (data) => {
	let res = data?.length > 0 ? data.map((d) => d.id) : null;
	return res;
};

const getPaginationPage = async (page) => {
	isSkeleton.value = true;
	await getCategories((page.page + 1), page.rows);
	isSkeleton.value = false;
}
</script>

<style>

</style>