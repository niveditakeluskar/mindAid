<template>
	<div v-html="patientCallHistory"></div>
</template>					 
<script>
import axios from 'axios';
export default {
	props: {
		patientId: Number,
	},
	data() {
		return {
			patientCallHistory: null,
		};
	},
	mounted() {
		this.fetchCallHistoryData();
	},
	methods: {
		fetchCallHistoryData() {
			axios.get(`/patients/patient-fetch-call-history-data/${this.patientId}/patient-call-history`)
				.then(response => {
					this.patientCallHistory = response.data;
				})
				.catch(error => {
					console.error('Error fetching data:', error);
				});
		},
	},
};
</script>