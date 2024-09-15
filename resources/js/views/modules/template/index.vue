<template>
    <div class="main-box">
		
		<Dialog :header="!templateId ? 'Add Template' : 'Edit Template'" v-model:visible="displaySaveTemplate" :draggable="false" modal style="width: 380px; max-width: 90vw;">
			<SaveTemplate :templateId="templateId" @closeModal="hideHandler(), filterData()" />
		</Dialog>
		
		<TopBar>
			<Button icon="pi pi-plus" class="btn-outline" iconClass="text-sm font-black" label="Add Template" @click="saveTemplateFn()" />
		</TopBar>

		<div class="content-box">
			<template v-if="templates?.data?.length > 0">
				<TableWrapper class="border-0 rounded-md bg-gray-50 pt-5 pb-4">
					<div class="content-box-inner py-0 pb-1">
						<div class="grid grid-cols-3 gap-6">
							<div v-for="(data, index) in templates.data" :key="index" class="template-preview pb-2">
								<Skeleton class="w-100 min-h-60 mb-2" v-if="isSkeleton"></Skeleton>
								<template v-else>
									<div class="btns">
										<a :href="$env.VITE_APP_URL+'/file/'+data.hash" class="btn-squre link-btn fill" target="_blank">
											<i class="pi pi-eye"></i>
										</a>
										<!-- <Button icon="pi pi-eye" class="btn-squre" @click="viewTemplateFn(data.id)" /> -->
										<Button icon="pi pi-pen-to-square" class="btn-squre" @click="saveTemplateFn(data.id)" />
										<Button icon="pi pi-trash" class="btn-squre" @click="deleteTemplateFn(data.id)" />
									</div>
									<iframe :src="$env.VITE_APP_URL+'/file/'+data.hash" frameborder="0" scrolling="no" cross-origin="anonymous" onload="iframeObjectFit(this)"></iframe>
									<span class="inline-block text-xs text-left mt-2">
										{{ data.name }}
									</span>
								</template>
							</div>
						</div>
					</div>
				</TableWrapper>
				<Paginator
					v-model:first="pagination_page"
					@page="getPaginationPage"
					:pageLinkSize="5"
					:rows="25"
					:totalRecords="templates.total"
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
import useTemplate from "@/services/TemplateService";
import SaveTemplate from "./save.vue";

const confirm = useConfirm();

const { templates, getTemplates, deleteTemplate } = useTemplate();

const isSkeleton = ref(false);
const loading = ref(false);
const pagination_page = ref(0);

const templateId = ref(null);

const displaySaveTemplate = ref(false);
const displayViewTemplate = ref(false);

onMounted(async () => {
	isSkeleton.value = true;
	await getTemplates();
	isSkeleton.value = false;
});

const saveTemplateFn = (id) => {
	displaySaveTemplate.value = true;
	templateId.value = id;
};

const viewTemplateFn = (id) => {
	displayViewTemplate.value = true;
	templateId.value = id;
};

const deleteTemplateFn = (id) => {
    confirm.require({
        accept: async () => {
			await deleteTemplate(id);
			await closeModal();
        },
    });
};

const filterData = async () => {
	pagination_page.value = 0;
	isSkeleton.value = true;
	await getTemplates();
	isSkeleton.value = false;
};

const hideHandler = async () => {
	displaySaveTemplate.value = false;
	displayViewTemplate.value = false;
};

const closeModal = async () => {
	await hideHandler();
	await filterData();
};

const getPaginationPage = async (page) => {
	isSkeleton.value = true;
	await getTemplates((page.page + 1), page.rows);
	isSkeleton.value = false;
}

</script>

<style>

</style>
