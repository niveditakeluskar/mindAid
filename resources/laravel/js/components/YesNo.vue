<template>
	<div>
		<slot></slot>
		<div>
			<div class="btn-group btn-group-toggle" data-toggle="buttons">
				<label :for="idYes" class="btn btn-outline-primary">
					<input type="radio" :name="name" :id="idYes" :data-feedback="feedback" autocomplete="off" value="1">
					{{ labelYes }}
				</label>
				<label :for="idNo" class="btn btn-outline-primary">
					<input type="radio" :name="name" :id="idNo" :data-feedback="feedback" autocomplete="off" value="0">
					{{ labelNo }}
				</label>
			</div>
			<div class="invalid-feedback visible" :data-feedback-area="feedback"></div>
		</div>
	</div>
</template>

<script>
export default {
	data () {
		return {
			"feedback": this.name + "-feedback",
			"idYes"   : this.name + "-yes",
			"idNo"    : this.name + "-no"
		}
	},
	props: {
		labelNo: {
			type   : String,
			default: "No"
		},
		labelYes: {
			type   : String,
			default: "Yes"
		},
		name: String
	}
}
</script>
