<template>
    <form @submit.prevent="saveCategoryFn()">
		<div class="flex flex-col gap-2 mb-4">
			<label for="name" class="block font-medium text-sm text-gray-700">
				Enter Name
			</label>
			<InputText id="name" v-model="category.name" placeholder="Enter Name" />
			<span class="input-error-msg" v-if="errors?.name">
				{{ errors.name[0] }}
			</span>
		</div>
		<div class="grid grid-cols-2 gap-3 mb-1">
			<label for="status" class="flex items-center justify-between gap-2 font-medium text-sm text-gray-700 bg-gray-50 rounded-md px-2 py-2 cursor-pointer">
				Active: <ToggleSwitch v-model="category.status" inputId="status" /> 
			</label>
			<div class="text-right">
				<Button type="submit" :label="!categoryId ? 'Add Category' : 'Edit Category'" class="min-w-28" :loading="loading" :disabled="loading" />
			</div>
			<div class="col-span-2">
				<span class="block input-error-msg" v-if="errors?.status">
					{{ errors.status[0] }}
				</span>
			</div>
		</div>
	</form>
</template>

<script setup>
import { ref, defineProps , onMounted } from "vue";
import useCategory from "@/services/CategoryService";

const props = defineProps({
	categoryId: Number,
});

const { errors, category, getCategory, storeCategory, updateCategory } = useCategory();

const loading = ref(false);

category.value = {
	status: true,
};

onMounted(async () => {
	loading.value = true;
	if(props.categoryId){
		await getCategory(props.categoryId);
	};
	loading.value = false;
});

const saveCategoryFn = async () => {
	loading.value = true;
	if(!props.categoryId){
		await storeCategory(category.value);
	}else{
		await updateCategory(props.categoryId, category.value);
	};
	loading.value = false;
};

</script>

<style>

</style>