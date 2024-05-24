<template>
<LayoutComponent ref="layoutComponentRef" >
    <div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Consolidate Billing Report</h4>
        </div>
    </div> 
</div>
<div class="separator-breadcrumb border-top"></div>

</LayoutComponent>
</template>
<script>
import {
  reactive,
  ref,
  onMounted,
  computed,
  watch,
  AgGridTable,
  onBeforeMount,
} from './../commonImports';
import LayoutComponent from './../LayoutComponent.vue'; 
export default {
  props: {
      
    },
  components: {
    LayoutComponent,
    AgGridTable,
},
setup(props) {
    const layoutComponentRef = ref(null);

    return {
      layoutComponentRef,
    };
},
};
</script>