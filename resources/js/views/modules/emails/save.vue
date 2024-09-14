<template>
    <form @submit.prevent="saveEmailFn()">
		<div class="flex flex-col gap-2 mb-4">
			<label for="category" class="block font-medium text-sm text-gray-700">
				Select Category
			</label>
			<MultiSelect id="category" v-model="email.category_ids" :options="all_categories" optionValue="id" optionLabel="name" placeholder="Select Category" display="chip" :filter="true" :loading="loading"
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
			<label for="name" class="block font-medium text-sm text-gray-700">
				Enter Name
			</label>
			<InputText id="name" v-model="email.name" placeholder="Enter Name" />
			<span class="input-error-msg" v-if="errors?.name">
				{{ errors.name[0] }}
			</span>
		</div>
		<div class="flex flex-col gap-2 mb-4">
			<label for="email" class="block font-medium text-sm text-gray-700">
				Enter Email
			</label>
			<InputText id="email" v-model="email.email" placeholder="Enter Email" />
			<span class="input-error-msg" v-if="errors?.email">
				{{ errors.email[0] }}
			</span>
		</div>
		<div class="grid grid-cols-2 gap-3 mb-4">
			<label for="subscribe" class="flex items-center justify-between gap-2 font-medium text-sm text-gray-700 bg-gray-50 rounded-md px-2 py-2 cursor-pointer">
				Subscribed: <ToggleSwitch v-model="email.subscribe" inputId="subscribe" />
			</label>
			<label for="status" class="flex items-center justify-between gap-2 font-medium text-sm text-gray-700 bg-gray-50 rounded-md px-2 py-2 cursor-pointer">
				Active: <ToggleSwitch v-model="email.status" inputId="status" /> 
			</label>
			<div class="col-span-2">
				<span class="input-error-msg" v-if="errors?.subscribe">
					{{ errors.subscribe[0] }}
				</span>
				<br>
				<span class="input-error-msg" v-if="errors?.status">
					{{ errors.status[0] }}
				</span>
			</div>
		</div>
		<div class="mb-4">
			<Button type="submit" :label="!emailId ? 'Add Email' : 'Edit Email'" class="min-w-28" :loading="loading" :disabled="loading" />
		</div>
	</form>
</template>

<script setup>
import { ref, defineProps , onMounted } from "vue";
import useCategory from "@/services/CategoryService";
import useEmail from "@/services/EmailService";

const props = defineProps({
	emailId: Number,
});

const { all_categories, getAllCategories } = useCategory();
const { errors, email, getEmail, storeEmail, updateEmail } = useEmail();

const loading = ref(false);

email.value = {
	subscribe: true,
	status: true,
};

onMounted(async () => {
	loading.value = true;
	await getAllCategories();
	if(props.emailId){
		await getEmail(props.emailId);
	};
	loading.value = false;
});

const saveEmailFn = async () => {
	loading.value = true;
	if(!props.emailId){
		await storeEmail(email.value);
	}else{
		await updateEmail(props.emailId, email.value);
	};
	loading.value = false;
};

</script>

<style>

</style>