<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Здания</h4>
                        <div class="card-tools">
                            <button class="btn btn-success" @click="modalForNewBuilding">Добавить
                                здание
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th width="35%" scope="col">Название</th>
                                <th width="35%" scope="col">Тип здания</th>
                                <th width="30%" scope="col"></th>
                            </tr>
                            </thead>
                            <tbody v-for="(building, key) in buildings">
                            <tr>
                                <th colspan="3" v-if="key == 1"><h5><b>Учебные корпуса</b></h5></th>
                                <th colspan="3" v-if="key == 2"><h5><b>Общежития</b></h5></th>
                                <th colspan="3" v-if="key == 3"><h5><b>Другие строения</b></h5></th>
                            </tr>
                            <tr v-for="build in orderedItems(building)" :key="build.position">
                                <td>{{ build.name }}</td>
                                <td>{{ build.group_name }}</td>
                                <td>
                                    <button class="btn btn-warning" @click="modalForChangeBuilding(build)">
                                        Редактировать
                                    </button>
                                    <button @click="deleteBuilding(build.id, build.name)"
                                            class="btn btn-danger">x
                                    </button>
                                    <button :disabled="build.position == 1" class="btn btn-info"
                                            @click="changePosition('up', build.id)"><i
                                        class="fas fa-arrow-up"></i></button>
                                    <button :disabled="build.position == building.length" class="btn btn-info"
                                            @click="changePosition('down', build.id)"><i
                                        class="fas fa-arrow-down"></i></button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="BuildingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="!editBuilding" class="modal-title">Добавление здания</h5>
                        <h5 v-show="editBuilding" class="modal-title">Изменение здания</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editBuilding ? updateBuilding() : createBuilding()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input placeholder="Название" v-model="form.name" type="text" name="name"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <input placeholder="Адрес" v-model="form.address" type="text" name="address"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('address') }">
                                <has-error :form="form" field="address"></has-error>
                            </div>
                            <div class="form-row">
                                <div class="col-12">
                                    <span class="badge">Код города</span>
                                </div>
                                <div class="form-group col-md-6">
                                    <select class="custom-select" v-model="form.phone.phonePrefix">
                                        <option value="1">Саранск +7(8342)</option>
                                        <option value="2">Рузаевка +7(83451)</option>
                                        <option value="3">Ковылкино +7(83453)</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <masked-input :mask="form.phone.phonePrefix == 1 ? '11-11-11' : '1-11-11'"
                                                  :disabled="form.phone.phonePrefix != 1 && form.phone.phonePrefix != 2 && form.phone.phonePrefix != 3"
                                                  placeholder="Номер телефона" v-model="form.phone.phoneNumber"
                                                  type="text"
                                                  class="form-control"
                                                  :class="{ 'is-invalid': form.errors.has('form.phone.phoneNumber') }"/>
                                </div>
                            </div>
                            <div v-show="!editBuilding" class="form-group">
                                <span class="badge">Тип здания</span>
                                <select class="custom-select" v-model="form.group_id" required>
                                    <option value="1">Учебный копус</option>
                                    <option value="2">Общежитие</option>
                                    <option value="3">Другое строение</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button v-show="!editBuilding" :disabled="form.phone.phonePrefix && form.phone.phoneNumber >= 0 " type="submit" class="btn btn-primary">Добавить здание
                            </button>
                            <button v-show="editBuilding" :disabled="form.phone.phonePrefix && form.phone.phoneNumber >= 0 " type="submit" class="btn btn-success">Сохранить изменения
                            </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import MaskedInput from "vue-masked-input";

    export default {
        components: {MaskedInput},
        data() {
            return {
                cityTypes: [
                    '+7(8342)',
                    '+7(83451)',
                    '+7(83453)'
                ],
                editBuilding: true,
                buildings: {},
                form: new Form({
                    id: '',
                    name: '',
                    address: '',
                    phone: {},
                    group_id: ''
                })
            }
        },

        methods: {
            updateBuilding(id) {
                this.$Progress.start();
                this.form.put('/api/building/' + this.form.id)
                    .then(() => {
                        toast({
                            type: 'success',
                            title: 'Здание обновлено'
                        });
                        $('#BuildingModal').modal('hide');
                        Fire.$emit('UpdateBuildingTable');
                        this.$Progress.finish();
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    })
            }
            ,

            changePosition(c, id) {
                axios.post('/api/building/' + id + '/up_down',
                    {method: c},
                ).then(({data}) => (this.loadBuildings()))
            },

            modalForNewBuilding() {
                this.editBuilding = false;
                this.form.reset();
                $('#BuildingModal').modal('show');
            }
            ,

            modalForChangeBuilding(building) {
                this.editBuilding = true;
                this.form.reset();
                $('#BuildingModal').modal('show');
                this.form.fill(building);
            }
            ,

            loadBuildings() {
                axios.get('/api/building').then(({data}) => (this.buildings = data));
            },

            orderedItems(items) {
                return _.orderBy(items, 'position', 'asc')
            },

            deleteBuilding(id, name) {
                swal({
                    title: 'Ву уверены?',
                    text: "После удаления будет невозможно восстановить данный элемент",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Удалить',
                    cancelButtonText: 'Отмена'
                }).then((result) => {

                        if (result.value) {
                            this.form.delete('/api/building/' + id).then(() => {
                                swal(
                                    'Здание ' + name + ' было удалено'
                                )
                                Fire.$emit('UpdateBuildingTable');

                            }).catch(() => {
                                swal(
                                    'Здание ' + name + 'не было удалено', 'warning'
                                )

                            })
                        }
                    }
                )
            }
            ,

            createBuilding() {
                console.log('create');
                this.$Progress.start()
                this.form.post('/api/building')
                    .then(() => {
                        Fire.$emit('UpdateBuildingTable');
                        $('#BuildingModal').modal('hide');
                        toast({
                            type: 'success',
                            title: 'Здание добавлено'
                        })
                        this.$Progress.finish();
                    })
                    .catch((e) => {
                        console.log(e);
                    })
            }
        },

        created() {
            this.loadBuildings();
            Fire.$on('UpdateBuildingTable', () => {
                this.loadBuildings();
            });
        }
    }
</script>
