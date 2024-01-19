


<template>
    <div class="col-md-6 form-group mb-3">
        <label class="col-md-12">Type <span class="error">*</span>
            <input name="type" type="text" class="form-control" placeholder="">
        </label>
        <div class="invalid-feedback" v-if="formErrors && formErrors.type" style="display: block;">{{ formErrors.type[0] }}</div>
    </div>
    <div class="col-md-6 form-group mb-3">
        <label class="col-md-12">Purpose <span class="error">*</span>
            <input name="purpose" type="text" class="form-control" placeholder="">
        </label>
        <div class="invalid-feedback" v-if="formErrors && formErrors.purpose" style="display: block;">{{ formErrors.purpose[0] }}</div>
    </div>
    <div class="col-md-6 form-group mb-3">
        <label class="col-md-12">Company Name <span class="error">*</span> 
            <input name="specify" type="text" class="form-control" placeholder="">
        </label>
        <div class="invalid-feedback" v-if="formErrors && formErrors.specify" style="display: block;">{{ formErrors.specify[0] }}</div>
    </div>
    <div class="col-md-6 form-group mb-3">
        <label class="col-md-12">Prescribing Provider <span class="error">*</span>
            <input name="brand" type="text" class="form-control"  placeholder="">
        </label>
        <div class="invalid-feedback" v-if="formErrors && formErrors.brand" style="display: block;">{{ formErrors.brand[0] }}</div>
    </div>
    <div class="col-md-12 form-group mb-3">
        <label class="col-md-12">Additional Notes
            <input name="additional_notes" type="text" class="form-control"  placeholder="">
        </label>
    </div>
</template>
<script>
import {
    ref,
    watch,
} from '../../commonImports';
export default {
    props: {
        formErrors: {
            type: Object,
            default: () => ({}),
        },
    },
    setup(props) {
        const formErrors = ref(props.formErrors);
        watch(
            () => props.formErrors,
            (newFormErrors, oldFormErrors) => {
                formErrors.value = newFormErrors;
            },
            { immediate: true }
        );
        return {
            formErrors,
        };
    },
};
</script>