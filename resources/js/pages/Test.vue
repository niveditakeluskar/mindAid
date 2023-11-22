<template>
    <div class="container">

        <Head title="Maintain Users" />
        <div class="row hidden-print">
            <ul class="breadcrumb">
                <li>System</li>
                <li>Users</li>
            </ul>
        </div>
        <div class="row">
            <DataTable :value="props.users" ref="dt" class="p-datatable-sm" showGridlines :scrollable="true"
                filterDisplay="row" scrollHeight="600px" dataKey="id" :metaKeySelection="false">
                <template #header>
                    <div class="header-table">
                        <h5>Users</h5>
                    </div>
                </template>
                <Column field="user_name" header="User Name" style="min-width: 10rem" :showFilterMenu="false">
                    <template #filter="{ filterModel, filterCallback }">
                        <InputText v-model="srchUserName" type="search" class="p-column-filter" />
                    </template>
                </Column>
                <Column field="first_name" header="First Name" style="min-width: 24rem" :showFilterMenu="false">
                    <template #filter="{ filterModel, filterCallback }">
                        <InputText v-model="srchFirstName" type="search" class="p-column-filter" />
                    </template>
                </Column>
                <Column field="last_name" header="Last Name" style="min-width: 12rem" :showFilterMenu="false">
                    <template #filter="{ filterModel, filterCallback }">
                        <InputText v-model="srchLastName" type="search" class="p-column-filter" />
                    </template>
                </Column>
                <Column field="email" header="Email" style="min-width: 24rem" :showFilterMenu="false">
                    <template #filter="{ filterModel, filterCallback }">
                        <InputText v-model="srchEmail" type="search" class="p-column-filter" />
                    </template>
                </Column>
                <Column field="actions" header="" style="min-width: 5rem" :showFilterMenu="false">
                    <template #body="slotProps">
                        <a :href="`/system/users/${slotProps.data.id}`" class="btn btn-primary">
                            <span title="Edit" class="glyphicon glyphicon-edit"></span>
                        </a>
                        <button class="btn btn-danger" style="margin-left: 1px;">
                            <span class="glyphicon glyphicon-remove-sign" @click="showDeleteModal(slotProps.data)"></span>
                        </button>
                    </template>
                </Column>
                <template #empty>
                    <p>No entries found for selected filters.</p>
                </template>
            </DataTable>
        </div>
        <div class="row">
            <div class="pull-right" style="display: flex; justify-content: space-around; gap: 10px; margin-top: 50px">
                <button class="btn btn-default" @click="arrayToCsv(exportColumns, exportData, 'GeneralLedgerEntries')">
                    Export Users
                </button>
                <a href="/system/users/create" class="btn btn-primary">Create New User</a>
            </div>
        </div>
    </div>
    <Modal :visible="deleteModal.visible" @close-modal-clicked="deleteModal.visible = false" title="Delete user?">
        <template v-slot:body>
            <p>Are you sure you want to delete this user?</p>
        </template>
        <template v-slot:footer>
            <button type="button" class="btn btn-default" @click="deleteModal.visible = false">Cancel</button>
            <button type="button" class="btn btn-danger" @click="deleteUser(deleteModal.selectedUser.id)">Delete
                User</button>
        </template>
    </Modal>
    <FlashAlert />
</template>
<script setup>
import { ref, onMounted, watch, reactive } from "vue";
import { router, useForm } from '@inertiajs/vue3'

import debounce from "lodash/debounce";
import FlashAlert from '../../../shared/FlashAlert.vue'
import Modal from '../../../shared/Modal.vue'

import DataTable from "primevue/datatable";
import Column from "primevue/column";
import InputText from "primevue/inputtext";

import { useUtils } from "../../../composables/utilities.js";
let { arrayToCsv } = useUtils();

let dt = ref();
let selectedUsers = ref([]);

let props = defineProps({
    users: Object,
    filters: Object,
});

let exportColumns = ref([]);
let exportData = ref([]);

onMounted(() => {
    setExportData();
});

let srchUserName = ref(props.filters?.srchUserName);
let srchFirstName = ref(props.filters?.srchFirstName);
let srchLastName = ref(props.filters?.srchLastName);
let srchEmail = ref(props.filters?.srchEmail);

watch(
    [
        srchUserName,
        srchFirstName,
        srchLastName,
        srchEmail,
    ],
    debounce(function (value) {
        pageAndFilter();
    }, 500)
);

function setExportData() {
    exportColumns.value = [
        { header: "UserName", dataKey: "user_name" },
        { header: "FirstName", dataKey: "first_name" },
        { header: "LastName", dataKey: "last_name" },
        { header: "Email", dataKey: "email" },
    ];

    exportData.value = [];
    exportData.value = props.users.map((item) => {
        return {
            user_name: item.user_name,
            first_name: item.first_name,
            last_name: item.last_name,
            email: item.email,
        };
    });
}

function pageAndFilter() {
    router.get(
        "users",
        {
            srchUserName: srchUserName.value,
            srchFirstName: srchFirstName.value,
            srchLastName: srchLastName.value,
            srchEmail: srchEmail.value,
        },
        { preserveState: true, replace: true }
    );
}

let deleteModal = reactive({
    visible: false,
    selectedUser: null,
})

function showDeleteModal(selectedUser) {
    deleteModal.visible = true
    deleteModal.selectedUser = selectedUser
}

function hideDeleteModal() {
    deleteModal.visible = false
    deleteModal.selectedUser = null
}

const form = useForm({})

function deleteUser(id) {
    form.delete(`/system/users/${id}`, {
        onError: () => $.notify(form.errors.user),
        onSuccess: () => hideDeleteModal(),
    })
}

</script>
<style scoped>
.header-table {
    display: flex;
    justify-content: space-between;
}

:deep(.p-text-right) {
    text-align: right;
}

:deep(.flex-right) {
    display: flex;
    justify-content: flex-end;
}

:deep(.flex-center) {
    display: flex;
    justify-content: center;
}

/* This rule takes the extra space away from the filter row columns */
:deep(.p-hidden-space, .p-link) {
    display: none;
}

:deep(.p-component) {
    font-size: 14px !important;
}
</style>
