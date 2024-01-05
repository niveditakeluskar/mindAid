<template>
    <div class="row">
        <div class="col-lg-12 mb-3">
            <form id="text_form" name="text_form" action="" method="post">
                <input type="hidden" name="uid" />
                <input type="hidden" name="patient_id" />
                <input type="hidden" name="start_time" value="00:00:00">
                <input type="hidden" name="end_time" value="00:00:00">
                <input type="hidden" name="module_id" />
                <input type="hidden" name="component_id" />
                <input type="hidden" name="stage_id" />
                <input type="hidden" name="step_id" value="0">
                <input type="hidden" name="form_name" value="text_form">
                <input type="hidden" name="content_title" value="text_form">
                <input type="hidden" name="template_type_id" value=""> 
                <div class="card">
                    <div class="card-body">
                       <Text :patientId="patientId" :moduleId="moduleId" :componentId="componentId" />
                    </div>
                    <div class="card-footer">
                        <div class="mc-footer">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" id="save-text" class="btn  btn-primary m-1">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <TextHistory />
        </div>
    </div>
</template>
<script>
import Text from '../../Messaging/Text.vue';
import TextHistory from '../../Messaging/TextHistory.vue';

export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number,
    },
    components: {
        Text,
        TextHistory,
    },
};
</script>