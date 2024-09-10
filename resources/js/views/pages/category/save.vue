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
		<div class="mb-4">
			<Button type="submit" :label="!categoryId ? 'Add Category' : 'Edit Category'" class="min-w-28" :loading="loading" :disabled="loading" />
		</div>
	</form>
</template>

<script setup>
import { ref, defineProps , onMounted, onBeforeMount } from "vue";
import useCategory from "@/services/CategoryService";

const props = defineProps({
	categoryId: Number,
})

const { errors, category, getCategory, storeCategory, updateCategory } = useCategory();

const loading = ref(false);

onBeforeMount(async () => {
	if(props.categoryId){
		await getCategory(props.categoryId);
	};
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