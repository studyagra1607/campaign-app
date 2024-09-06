<template>
    <div class="content-box h-full">
		<div class="grow flex flex-col border mb-2">
			<div class="h-px grow overflow-auto scroll_bar">
				<DataTable
					:value="emails"
					dataKey="id"
					class="p-datatable-gridlines"
					:rowHover="true"
					:loading="loading"
					responsiveLayout="scroll"
				>
					<!-- <template #header>
						<div class="search-filter-box" style="--sf-width: 25%; --sf-gap: 1rem;">
							
						</div>
					</template> -->

					<template #empty>
						No records found.
					</template>

					<template #loading>
						Loading data. Please wait.
					</template>

					<Column header="Name">
						<template #body v-if="isSkeleton">
							<Skeleton></Skeleton>
						</template>
						<template #body="{ data }" v-else>
							Bharat Kushwaha
						</template>
					</Column>

					<Column header="Email">
						<template #body v-if="isSkeleton">
							<Skeleton></Skeleton>
						</template>
						<template #body="{ data }" v-else>
							bharat@gmail.com
						</template>
					</Column>

					<Column header="Status" class="w-[20%]">
						<template #body v-if="isSkeleton">
							<Skeleton></Skeleton>
						</template>
						<template #body="{ data }" v-else>
							<Tag severity="success" value="Subscribed" class="text-xs"></Tag>
						</template>
					</Column>

					<Column header="Actions" class="w-[20%]">
						<template #body v-if="isSkeleton">
							<Skeleton></Skeleton>
						</template>
						<template #body="{ data }" v-else>
							<ToggleSwitch v-model="data.status" @change="changeStatusFn(data.id)"/>
						</template>
					</Column>
				</DataTable>
			</div>
		</div>
		<Paginator :rows="10" :totalRecords="120" :rowsPerPageOptions="[10, 20, 30]"></Paginator>
	</div>
</template>

<script setup>
import { ref, onMounted } from "vue";

const isSkeleton = ref(false);
const loading = ref(false);

const emails = ref([
	{
		id: 1
	},
]);

const changeStatusFn = async (id) => {
	loading.value = true;
	
	loading.value = false;
};
</script>

<style>
.p-dialog-content{
	height: 100%;
}
</style>