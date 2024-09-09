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
			<Button type="submit" label="Add Category" class="min-w-28" :loading="loading" :disabled="loading" />
		</div>
	</form>
</template>

<script setup>
import { ref, onMounted } from "vue";
import useCategory from "@/services/CategoryService";

const { errors, storeCategory } = useCategory();

const loading = ref(false);

const category = ref({});

const saveCategoryFn = async () => {
	loading.value = true;
	await storeCategory(category.value);
	loading.value = false;
};

</script>

<style>

</style>