<template>
    <form @submit.prevent="uploadEmailCsvFn()">
		<div class="flex flex-col gap-2 mb-4">
			<label for="category" class="block font-medium text-sm text-gray-700">
				Select Category
			</label>
			<MultiSelect id="category" v-model="uploadEmailCsvData.category_ids" :options="all_categories" optionValue="id" optionLabel="name" placeholder="Select Category" display="chip" :filter="true" :loading="loading"
			:pt="{
				label: {
					class: 'overflow-auto scroll_bar_none',
				}
			}"
			/>
			<span class="input-error-msg" v-if="errors?.category_ids">
				{{ errors.category_ids[0] }}
			</span>
		</div>
		<div class="flex flex-col gap-2 mb-4">
			<label for="file" class="block font-medium text-sm text-gray-700">
				Upload CSV or File
			</label>
			<div class="file-dropdown">
				<FileUpload accept=".xls,.xlsx,.csv" :fileLimit="fileLimit" :multiple="true">
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
				<div class="text-[.68rem] mt-1" v-if="visibleOnec">
					<i class="pi pi-exclamation-circle text-[.6rem]"></i>
					Upload a valid file in CSV, XLS, or XLSX <a :href="$env.VITE_APP_URL+'/assets/sample-email-csv.csv'" class="underline" download="sample-email-csv.csv">sample</a>.
				</div>
			</div>
			<span class="input-error-msg" v-if="errors?.file" v-html="errors?.file[0]"></span>
		</div>
		<div class="mb-4">
			<Button type="submit" label="Upload" class="min-w-28" :loading="loading" :disabled="loading || uploadEmailCsvData?.file?.length > fileLimit" />
		</div>
	</form>
</template>

<script setup>
import { ref, watch, onMounted } from "vue";
import useCategory from "@/services/CategoryService";
import useEmails from "@/services/EmailService";

const { all_categories, getAllCategories } = useCategory();
const { errors, uploadEmailCsv } = useEmails();

const loading = ref(false);

const visibleOnec = ref(true);

const fileErrors = ref([]);

const fileLimit = ref(1);

const uploadEmailCsvData = ref({});

onMounted(async () => {
	loading.value = true;
	await getAllCategories();
	loading.value = false;
});

const uploadEmailCsvFn = async () => {
	loading.value = true;
	await uploadEmailCsv(uploadEmailCsvData.value);
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
	uploadEmailCsvData.value.file = files[0];
};

const setFileErrors = (msg) => {
	fileErrors.value = msg;
};

watch([errors, fileErrors], ([newErrors, newFileErrors]) => {
	// (errors.length <= 0) && (fileErrors.length <= 0) 
    if (newErrors?.length != 0 || newFileErrors?.length != 0) {
        visibleOnec.value = false;
    };
});
</script>

<style>

</style>