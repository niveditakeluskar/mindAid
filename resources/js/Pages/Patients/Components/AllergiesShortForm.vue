<template>
    <div class="col-md-12 form-group mb-3">
        <label class="col-md-6">Specify <span class="error">*</span>
            <input  id="specify" name="specify" type="text" class="form-control" placeholder="">
        </label>
        <div class="invalid-feedback" v-if="formErrors && formErrors.specify" style="display: block;">{{ formErrors.specify[0] }}</div>
    
        <label class="col-md-6">Type of Reaction <span class="error">*</span>
            <input id="type_of_reactions" name="type_of_reactions" type="text" class="form-control" placeholder="">
        </label>
        <div class="invalid-feedback" v-if="formErrors && formErrors.type_of_reactions" style="display: block;">{{ formErrors.type_of_reactions[0] }}</div>
    </div>
    <div class="col-md-12 form-group mb-3">
        <label class="col-md-6">Severity <span class="error">*</span> 
            <input id="severity" name="severity" type="text" class="form-control" placeholder="">
        </label>
        <div class="invalid-feedback" v-if="formErrors && formErrors.severity" style="display: block;">{{ formErrors.severity[0] }}</div>

        <label class="col-md-6">Course of Treatment <span class="error">*</span>
            <input id="course_of_treatment" name="course_of_treatment" type="text" class="form-control"  placeholder="">
        </label>
        <div class="invalid-feedback" v-if="formErrors && formErrors.course_of_treatment" style="display: block;">{{ formErrors.course_of_treatment[0] }}</div>
    </div>
    <div class="col-md-12 form-group mb-3">
        <label class="col-md-12">Additional Notes
            <textarea id="notes" name="notes" class="form-control"  placeholder=""></textarea>
        </label>
        <div class="invalid-feedback" v-if="formErrors && formErrors.notes" style="display: block;">{{ formErrors.notes[0] }}</div>
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