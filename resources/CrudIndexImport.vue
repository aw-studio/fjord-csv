<template>
    <div class="d-inline-block">
        <b-button size="sm" variant="primary" @click='toggleModal'>
            <fa-icon icon="file-import" />
            Import CSV
        </b-button>
        <b-modal id="import-modal" title="Import CSV">

            <div class="input-group"  :style="{display: csvData ? 'none' : 'flex'}">
                <div class="custom-file">
                    <input type="file"  @change="fileInput" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                </div>
            </div>

            <table v-if="fillable && csvData" class="table">
                <tr>
                    <th>
                        Model-Fields
                    </th>
                    <th>
                        CSV-Fields
                    </th>
                </tr>
                <tr v-for="row in fillable">
                    <td>
                        {{row}}
                    </td>
                    <td>
                        <div class="form-group mb-0">
                            <select v-if="csvData" v-model="map[row]" class="form-control">
                                <option>Select Field</option>
                                <option v-for="field in csvData.meta.fields" :selected="field == row">{{field}}</option>
                            </select>
                        </div>
                    </td>
                </tr>
            </table>

            <template v-slot:modal-footer>
                <b-button variant="primary" @click="uplodad" v-if="fillable && csvData">Import</b-button>
                <b-button @click="cancel">Cancel</b-button>
            </template>
        </b-modal>
    </div>
</template>

<script>
import { mapGetters } from "vuex"
import * as Papa from 'papaparse'
export default {
    name: 'CrudIndexImport',
    props: {
        formConfig: {
            type: Object
        }
    },
    data(){
        return {
            fillable: null,
            csvData: null,
            uploadData: [],
            map: {}
        }
    },
    computed: {
        ...mapGetters(['baseURL'])
    },
    methods: {
        cancel(){
            this.$bvModal.hide('import-modal')
        },
        toggleModal(){
            this.csvData = null
            this.uploadData = []

            this.$bvModal.show('import-modal')
            this.getFillable()
        },
        fileInput(e){
            let file = e.target.files[0];

            let reader = new FileReader();
            reader.readAsText(file);
            reader.onload = (event) => {
                let csvData = event.target.result;
                let data = Papa.parse(csvData, {
                    header : true,
                    skipEmptyLines: true
                });
                this.csvData = data
                this.prepareMap(data.meta.fields)
            }

        },
        prepareMap(fields){
            for (var i = 0; i < this.fillable.length; i++) {
                let key = this.fillable[i]
                if(fields.includes(key)){
                    this.map[key] = key
                }
            }
        },
        uplodad(){
            let map = this.swap(this.map)

            for (var i = 0; i < this.csvData.data.length; i++) {
                this.uploadData.push(
                    this.renameKeys(map, this.csvData.data[i])
                )
            }

            this.importCsv();
        },
        swap(json){
            let ret = {};
            for(let key in json){
                ret[json[key]] = key;
            }
            return ret;
        },
        renameKeys(keysMap, obj){
            return Object.keys(obj).reduce((acc, key) => {

                const renamedObject = {
                    [keysMap[key] || key]: obj[key]
                };

                return {
                    ...acc,
                    ...renamedObject
                };
            }, {});
        },
        async importCsv(){
            try {
                const { data } = await axios.post(
                    `${this.formConfig.names.table}/import`,
                    {
                        model: this.formConfig.model,
                        data: this.uploadData
                    }
                );
                this.$notify({
                    group: 'general',
                    type: 'success',
                    title: `Success`,
                    text: data.message
                });
                setTimeout(function(){
                    location.reload();
                }, 1500)
            } catch (e) {
                this.$notify({
                    group: 'general',
                    type: 'danger',
                    title: e,
                    text: e.response.data.message,
                    duration: -1
                });
            } finally {

            }
        },
        async getFillable(){
            try {
                const { data } = await axios.post(`${this.formConfig.names.table}/getFillable`, {model: this.formConfig.model})
                this.fillable = data
            } catch (e) {
                console.log(e);
            } finally {

            }
        }
    }
};
</script>
