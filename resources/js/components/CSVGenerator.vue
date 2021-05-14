<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">Table to CSV Generator</div>
                    <div class="card-body">
                        <div class="table-wrapper">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th v-for="(column, columnIndex) in columns">
                                        <input type="text"
                                               :disabled="loading"
                                               class="form-control c-input"
                                               :class="{'is-invalid':headerRequired && !columns[columnIndex]}"
                                               v-model="columns[columnIndex]"
                                        />
                                    </th>
                                    <th>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(row, rowIndex) in rows">
                                    <td v-for="(columnValue, columnIndex) in row">
                                        <input type="text" :disabled="loading" class="form-control"
                                               v-model="row[columnIndex]"/>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger" @click="removeRow(rowIndex)">
                                            -
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-inline">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" :disabled="loading" @click="addRow">Add
                                    Row
                                </button>
                            </div>
                            <span class="divider">|</span>
                            <div class="input-group add-column-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Add</span>
                                </div>
                                <input type="text" class=" form-control" :disabled="loading" v-model="addColumnCnt"/>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" @click="addColumns"
                                            :disabled="!addColumnInputValid || loading">Column(s)
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-outline-primary" type="button" @click="submit()"
                                :disabled="!tableValid || loading">Export
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CSVGenerator",
    props: {
        headerRequired: {
            type: Boolean,
            default: true
        }
    },
    data() {
        return {
            columns: [
                'first_name',
                'last_name',
                'emailAddress',
            ],
            rows: [
                [
                    'John',
                    'Doe',
                    'john.doe@example.com'
                ],
                [
                    'John',
                    'Doe',
                    'john.doe@example.com'
                ],
            ],
            addColumnCnt: 1,
            loading: false //if request is slow
        }
    },

    methods: {
        addRow() {
            this.$data.rows.push(Array(this.$data.columns.length).fill(""));
        },
        removeRow(rowIndex) {
            this.$data.rows.splice(rowIndex, 1);
        },
        addColumns() {
            let cnt = parseInt(this.$data.addColumnCnt);
            while (cnt > 0) {
                this.$data.columns.push('');
                cnt--;
                this.$data.rows.forEach(v => {
                    v.push('');
                });
            }
        },
        submit() {
            this.loading = true;
            return axios.post('/api/csv-export', {columns: this.$data.columns, rows: this.$data.rows})
                .then(response => {
                    const blob = new Blob([response.data], {type: 'text/csv;charset=utf-8;'}),
                        filename = 'export.csv';
                    if (navigator.msSaveBlob) { // IE 10+
                        navigator.msSaveBlob(blob, filename);
                    } else {
                        let link = document.createElement("a");
                        if (link.download !== undefined) {
                            let url = URL.createObjectURL(blob);
                            link.setAttribute("href", url);
                            link.setAttribute("download", filename);
                            link.style.visibility = 'hidden';
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        }
                    }
                })
                .catch(error => {
                    alert(error.response.data.error);
                })
                .finally(() => {
                    this.$set(this, 'loading', false);
                });
        }
    },
    computed: {
        addColumnInputValid() {
            return parseInt(this.$data.addColumnCnt) > 0;
        },
        tableValid() {
            let valid = true;
            if (this.$props.headerRequired) {
                this.$data.columns.forEach(v => {
                    if (!v) {
                        valid = false;
                    }
                })
            }
            if (!this.$data.rows.length) {
                valid = false;
            }
            return valid;
        },
    }
}
</script>

<style scoped>
.table-wrapper {
    overflow-x: auto;
}

.c-input {
    min-width: 120px;
}

.divider {
    padding: 0 16px;
}

.add-column-group {
    width: 200px;
}
</style>
