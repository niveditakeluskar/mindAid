<template>
    <div class="card">
        <form id="relationship_form" name="relationship_form" action="" method="post"> 
                <div class="card-body">
                    <input type="hidden" name="uid" />
                    <input type="hidden" name="patient_id" />
                    <input type="hidden" name="start_time" value="00:00:00">
                    <input type="hidden" name="end_time" value="00:00:00">
                    <input type="hidden" name="module_id" />
                    <input type="hidden" name="component_id" />
                    <input type="hidden" name="stage_id" />
                    <input type="hidden" name="hid_stage_id" />
                    <input type="hidden" name="form_name" value="relationship_form">
                    <div class="alert alert-success" id="success-alert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong> Relationship data saved successfully! </strong><span id="text"></span>
                    </div>
                    <div v-html="RelationshipQuestionnaire"></div>
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit" id="save-question" class="btn btn-primary m-1">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number
    },
    data() {
        return {
            RelationshipQuestionnaire: null,
        };
    },
    mounted() {
        this.fetchData();
    },
    methods: {
        fetchData() {
            axios.get(`/patients/patient-relationship-questionnaire/${this.patientId}/${this.moduleId}/${this.componentId}/patient-relationship-questionnaire`)
                .then(response => {
                    this.RelationshipQuestionnaire = response.data;
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        },
    },
};
</script>