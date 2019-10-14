<template>
    <b-dropdown-item @click="exportItems">
        exportItems
    </b-dropdown-item>
</template>

<script>
import { mapGetters } from "vuex"
export default {
    name: 'CrudIndexExport',
    props: {
        formConfig: {
            required: true,
            type: Object
        },
        selectedItems: {
            type: Array,
            required: true
        },
        sendAction: {
            required: true
        }
    },
    computed: {
        ...mapGetters(['baseURL'])
    },
    methods: {
        async exportItems(){
            let options = {
                responseType: 'blob',
            }
            try {
                let response = await axios.post(`${this.formConfig.names.table}/export`, {ids: this.selectedItems}, options)

                this.forceFileDownload(response);
            } catch (e) {
                console.log(e);
            } finally {

            }
        },
        forceFileDownload(response) {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', `${this.formConfig.names.table}.csv`);
            document.body.appendChild(link);
            link.click();
        }

    }
};
</script>
