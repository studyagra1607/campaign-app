<template>
    <div class="main-box">

		<Dialog :header="'Upload Emails'" v-model:visible="displayUploadEmail" :draggable="false" modal :style="{ width: '25vw' }">
			<UploadEmail @closeModal="hideHandler(), filterData()" />
		</Dialog>
		
		<Dialog :header="!emailId ? 'Add Email' : 'Edit Email'" v-model:visible="displaySaveEmail" :draggable="false" modal :style="{ width: '25vw' }">
			<SaveEmail :emailId="emailId" @closeModal="hideHandler(), filterData()" />
		</Dialog>
		
		<TopBar>
			<div class="flex flex-wrap gap-2">
				<Button icon="pi pi-trash" class="btn-outline" :label="`Delete (${selectedEmails?.length})`" :loading="loading" @click="deleteEmailFn()" v-if="selectedEmails.length > 0" 
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
				<Button icon="pi pi-plus" class="btn-outline" iconClass="text-sm font-black" label="Add Email" @click="saveEmailFn()" />
				<Button icon="pi pi-download" iconClass="text-sm font-black" label="Import Emails" @click="uploadEmailFn()" />
			</div>
		</TopBar>

		<div class="content-box">
			<template v-if="emails?.data?.length > 0">
				<TableWrapper>
					<DataTable
					v-model:selection="selectedEmails"
					tableClass="min-w-[1400px]"
					class="p-datatable-gridlines"
					editMode="cell"
					dataKey="id"
					:rowHover="false"
					:loading="loading"
					:value="emails.data"
					:pt="{
						tableContainer: 'scroll_bar_none'
					}"
					>
						<template #empty>
							No records found.
						</template>

						<template #loading>
							Loading data. Please wait.
						</template>

						<Column selectionMode="multiple" headerStyle="width: 3rem">

						</Column>

						<Column header="Name" class="w-[14%]">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data }" v-else>
								{{ data.name }}
							</template>
						</Column>

						<Column header="Email" class="w-[20%]">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data }" v-else>
								{{ data.email }}
							</template>
						</Column>

						<Column header="Category" class="w-[20%]">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data }" v-else>
								<template v-if="data?.categories?.length > 0">
									<div class="card flex flex-wrap gap-2">
										<template v-for="(category, index) of data.categories" :key="index">
											<Chip :label="category.name" class="text-[.75rem] px-2 py-1" />
										</template>
									</div>
								</template>
								<template v-else>
									No Category
								</template>
							</template>
							<template #editor="{ data, index }">
								<MultiSelect id="category" class="w-full" v-model="data.category_ids" :options="all_categories" optionValue="id" optionLabel="name" placeholder="Select Category" display="chip" :filter="true" :loading="loading" @change="changeCategoryFn(data, index)"
								:pt="{
									label: {
										class: 'flex-wrap overflow-auto scroll_bar_none',
									},
									chipItem: {
										class: 'text-[.75rem]',
									}
								}"
								/>
							</template>
						</Column>
						
						<Column header="Subscribe">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data }" v-else>
								<Tag severity="success" value="Subscribed" class="text-xs" v-if="data.subscribe"></Tag>
								<Tag severity="danger" value="Unsubscribed" class="text-xs" v-else></Tag>
							</template>
						</Column>

						<Column header="Status">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data, index }" v-else>
								<ToggleSwitch v-model="data.status" @change="changeStatusFn(data, index)"/>
							</template>
						</Column>

						<Column header="Actions">
							<template #body v-if="isSkeleton">
								<Skeleton></Skeleton>
							</template>
							<template #body="{ data }" v-else>
								<div class="inline-flex flex-wrap gap-2">
									<Button icon="pi pi-pen-to-square" class="btn-squre" @click="saveEmailFn(data.id)" />
									<Button icon="pi pi-trash" class="btn-squre" @click="deleteEmailFn(data.id)" />
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
					:totalRecords="emails.total"
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
import useEmail from "@/services/EmailService";
import UploadEmail from "./upload.vue";
import SaveEmail from "./save.vue";

const confirm = useConfirm();

const { all_categories, getAllCategories } = useCategory();
const { email, emails, getEmails, updateEmail, deleteEmail } = useEmail();

const isSkeleton = ref(false);
const loading = ref(false);
const pagination_page = ref(0);

const selectedEmails = ref([]);
const emailId = ref(null);

const displayUploadEmail = ref(false);
const displaySaveEmail = ref(false);

onMounted(async () => {
	isSkeleton.value = true;
	await getAllCategories();
	await getEmails();
	isSkeleton.value = false;
});

const uploadEmailFn = () => {
	displayUploadEmail.value = true;
};

const saveEmailFn = (id) => {
	displaySaveEmail.value = true;
	emailId.value = id;
};

const deleteEmailFn = (id) => {
	confirm.require({
        accept: async () => {
			await deleteEmail(id ?? getMapData(selectedEmails.value));
			await closeModal();
        },
    });
};

const changeCategoryFn = async (data, index) => {
	await updateEmail(data.id, data);
	emails.value.data[index] = email.value;
};

const changeStatusFn = async (data, index) => {
	await updateEmail(data.id, data);
	emails.value.data[index] = email.value;
};

const filterData = async () => {
	pagination_page.value = 0;
	isSkeleton.value = true;
	selectedEmails.value = [];
	await getEmails();
	isSkeleton.value = false;
};

const hideHandler = async () => {
	displayUploadEmail.value = false;
	displaySaveEmail.value = false;
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
	await getEmails((page.page + 1), page.rows);
	isSkeleton.value = false;
}
</script>

<style>

</style>