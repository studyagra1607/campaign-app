<template>
    <form @submit.prevent="saveTemplateFn()">
		<div class="flex flex-col gap-2 mb-4">
			<label for="name" class="block font-medium text-sm text-gray-700">
				Enter Name
			</label>
			<InputText id="name" v-model="template.name" placeholder="Enter Name" />
			<span class="input-error-msg" v-if="errors?.name">
				{{ errors.name[0] }}
			</span>
		</div>
		<div class="flex flex-col gap-2 mb-4">
			<label for="file" class="block font-medium text-sm text-gray-700">
				Upload Template
			</label>
			<div class="file-dropdown">
				<FileUpload accept=".htm,.html" :fileLimit="fileLimit" :multiple="true">
					<template #header="{ chooseCallback }">
						<div ref="chooseFiles" @click="chooseCallback()"></div>
					</template>
					<template #content="{ messages, files, removeFileCallback }">
						{{ setSelectedFiles(files) }}
						{{ setFileErrors(messages) }}
						<div v-if="files.length > 0" class="flex flex-col gap-1">
							<div v-for="(file, index) of files" :key="index" class="flex flex-wrap items-center justify-between bg-gray-950 text-gray-100 rounded-md px-3 p-1">
								<div class="inline-flex flex-col justify-center">
									<span class="text-[.882rem] mt-[.15rem]">{{ sortName(file.name) }}</span>
									<span class="text-[.75rem] pb-1 mt-[.03rem] text-gray-300">{{ formatSize(file.size) }}</span>
								</div>
								<div>
									<Button icon="pi pi-times" iconClass="font-semibold" class="btn-squre bg-gray-50 text-gray-950 hover:bg-gray-200" @click="removeFileCallback(index)" />
								</div>
							</div>
						</div>
						<div v-else class="text-center py-3">
							Drag and Drop your files or <a href="javascript:void(0)" class="underline decoration-1" @click="$refs.chooseFiles.click()">Browse</a>
						</div>
					</template>
				</FileUpload>
				<div class="flex flex-col gap-2 mt-3" v-if="fileErrors.length > 0">
					<span v-for="(data, index) in fileErrors" :key="index" class="block text-xs font-normal rounded-md border border-red-600 bg-red-100 text-red-600 px-2 py-1">
						{{ data }}
					</span>
				</div>
			</div>
			<span class="input-error-msg" v-if="errors?.file" v-html="errors?.file[0]"></span>
		</div>
		<div class="mb-4">
			<Button type="submit" label="Add Template" class="min-w-28" :loading="loading" :disabled="loading || template?.file?.length > fileLimit" />
		</div>
	</form>
</template>

<script setup>
import { ref, defineProps, onMounted } from "vue";
import useTemplate from "@/services/TemplateService";

const props = defineProps({
	templateId: Number,
});

const { errors, template, getTemplate, updateTemplate, storeTemplate } = useTemplate();

const loading = ref(false);

const fileErrors = ref([]);

const fileLimit = ref(1);

onMounted(async () => {
	if(props.templateId){
		await getTemplate(props.templateId);
	};
});

const saveTemplateFn = async () => {
	loading.value = true;
	if(!props.templateId){
		await storeTemplate(template.value);
	}else{
		await updateTemplate(props.templateId, template.value);
	};
	loading.value = false;
};

const formatSize = (bytes) => {
    if (bytes === 0) return "0 B";
    const k = 1024;
    const sizes = ["B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
};

const sortName = (name, start, end) => {
    var start = start ? start : 5; 
    var end = end ? end : 5; 
    var start_str = name.substr(0, start); 
    var end_str = name.substr(name.lastIndexOf(".") - end); 

    if((name.length - 1) > (start + end)){
        return start_str.trim() + "..." + end_str.trim();
    }
    else{
        return name;
    };
};

const setSelectedFiles = (files) => {
	template.value.file = files[0];
};

const setFileErrors = (msg) => {
	fileErrors.value = msg;
};
</script>

<style>

</style>