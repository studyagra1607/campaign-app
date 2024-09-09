<template>
    <div class="main-box">
		
		<Dialog :header="'Add Template'" v-model:visible="displaySaveTemplate" :draggable="false" modal :style="{ width: '25vw' }">
			<SaveTemplate />
		</Dialog>
		
		<TopBar>
			<Button icon="pi pi-plus" class="btn-outline" iconClass="text-sm font-black" label="Add Template" @click="saveTemplateFn()" />
		</TopBar>

		<div class="content-box">
			<div class="content-box-inner">
				<div class="grid grid-cols-3 gap-6">
					<div v-for="(data, index) in templates" :key="index" class="template-preview pb-2">
						<div class="btns">
							<Button icon="pi pi-eye" class="btn-squre" @click="viewTemplateFn(data.id)" />
							<Button icon="pi pi-pen-to-square" class="btn-squre" @click="saveTemplateFn(data.id)" />
							<Button icon="pi pi-trash" class="btn-squre" @click="deleteTemplateFn(data.id)" />
						</div>
						<iframe src="https://bharatcv.com/cv/" frameborder="0" scrolling="no"></iframe>
						<span class="inline-block text-xs text-left mt-2">
							Template Name
						</span>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</template>

<script setup>
import { ref } from "vue";
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";
import SaveTemplate from "./save.vue";

const toast = useToast();
const confirm = useConfirm();

const displaySaveTemplate = ref(false);
const displayViewTemplate = ref(false);

const templates = ref([
	{
		id: 1
	},
	{
		id: 1
	},
	{
		id: 1
	},
	{
		id: 1
	},
	{
		id: 1
	},
	{
		id: 1
	},
	{
		id: 1
	},
	{
		id: 1
	},
	{
		id: 1
	},
	{
		id: 1
	},
	{
		id: 1
	},
]);

const saveTemplateFn = (id) => {
	displaySaveTemplate.value = true;
};

const viewTemplateFn = (id) => {
	displayViewTemplate.value = true;
};

const deleteTemplateFn = (id) => {
    confirm.require({
        accept: () => {
			toast.add({ severity: 'contrast', summary: 'Deleted successfully!', life: 3000 });
        },
    });
};

</script>

<style>

</style>