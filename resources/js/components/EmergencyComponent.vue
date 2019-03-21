<template xmlns="http://www.w3.org/1999/html">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Дежурные службы</h4>
                        <div class="card-tools">
                            <button class="btn btn-success" @click="modalForNewEmergency">Добавить
                                службу
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th width="30%" scope="col">Название</th>
                                <th width="25%" scope="col">Внутренние номера</th>
                                <th width="20%" scope="col">Внешние номера</th>
                                <th width="25%" scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="em in emergency">
                                <td>{{em.name}}</td>
                                <td><span v-for="ip in em.ip_phone"
                                          v-if="em.ip !== []">
                                            {{ ip }}<br></span></td>
                                <td><span v-for="phone in em.phone" v-if="em.phone">
                                        {{ cityTypes[phone.phonePrefix-1] }}
                                        {{ phone.phoneNumber}}
                                            <br></span></td>
                                <td>
                                    <button class="btn btn-warning" @click="modalForChangeEmergency(em)">
                                        Редактировать
                                    </button>
                                    <button @click="deleteEmergency(em.id, em.name)"
                                            class="btn btn-danger">x
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="EmergencyEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Добавить сотрудника</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editEmergency ? updateEmergency() : createEmergency()">
                        <div class="modal-body">
                            <div class="under-div  ">
                                <div class="form-group">
                                    <input type="text" v-model="form.name" placeholder="Название" class="form-control"
                                           required>
                                </div>
                            </div>
                            <div class="under-div  ">
                                <div class="form-row" v-for="(phn,index) in form.phone">
                                    <div class="form-group col-md-4">
                                        <select class="custom-select" v-model="form.phone[index].phonePrefix">
                                            <option value="1">Саранск +7(8342)</option>
                                            <option value="2">Рузаевка +7(83451)</option>
                                            <option value="3">Ковылкино +7(83453)</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <masked-input
                                            required
                                            :mask="form.phone[index].phonePrefix == 1 ? '11-11-11' : '1-11-11'"
                                            placeholder="Номер телефона" v-model="form.phone[index].phoneNumber"
                                            type="text"
                                            class="form-control float-left col-8 mr-1"
                                            :class="{ 'is-invalid': form.errors.has('form.phone[index].phoneNumber') }"/>
                                        <has-error :form="form" field="form.phone[index].phoneNumber"></has-error>
                                        <button @click.prevent="deletePhone(index)" type="button"
                                                class="form-control btn btn-danger float-left col-2">&#10006;
                                        </button>
                                    </div>
                                    <div class="form-group col-md-4">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-4 m-0">
                                        <button type="button" class="btn btn-primary col-12"
                                                @click.prevent="addPhone()">
                                            Добавить внешний номер
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="under-div  ">
                                <div class="form-row" v-for="(ipPhone,index) in form.ip_phone">
                                    <div class="form-group col-md-4">
                                        <masked-input
                                            :mask="'1111'"
                                            maxlength="4" placeholder="Внутренний номер" v-model="form.ip_phone[index]"
                                            type="tel"
                                            required
                                            class="form-control col-8 float-left mr-1"
                                            :class="{ 'is-invalid': form.errors.has('form.ip_phone[index]') }"/>
                                        <has-error :form="form" field="form.ip_phone[index]"></has-error>
                                        <button @click="deleteIpPhone(index)" type="button"
                                                class="btn btn-danger float-left col-2">&#10006;
                                        </button>
                                    </div>
                                    <div class="form-group col-md-4">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-4 m-0">
                                        <button @click="addIpPhone()" class="btn btn-primary col-12" type="button">
                                            Добавить
                                            внутренний номер
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button v-show="!editEmergency"
                                    type="submit"
                                    class="btn btn-success">Добавить службу
                            </button>
                            <button v-show="editEmergency"
                                    type="submit"
                                    class="btn btn-primary">Сохранить изменения
                            </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {MaskedInput, ModelSelect, ModelListSelect, VueGoodTable},
        data() {
            return {
                roomTypes: [
                    'кабинет',
                    'этаж'
                ],
                cityTypes: [
                    '+7(8342)',
                    '+7(83451)',
                    '+7(83453)'
                ],
                isActive: false,
                emergency: [],
                editEmergency: false,
                form: new Form({ //'fio', 'position', 'phone', 'ip_phone', 'Emergency_id', 'group_id', 'room', 'room_type', 'email'
                    id: '',
                    name: '',
                    phone: [],
                    ip_phone: [],
                })
            }
        },

        methods: {
            searchIt: _.debounce(() => {
                Fire.$emit('searching');
            }, 300),

            addMail() {
                this.form.email.push('');
                this.form.clear();
            },

            deleteMail(index) {
                this.form.email.splice(index, 1);
            },

            addIpPhone() {
                this.form.ip_phone.push('');
                this.form.clear();
            },

            deleteIpPhone(index) {
                this.form.ip_phone.splice(index, 1);
            },

            addPhone() {
                this.form.phone.push({
                    phonePrefix: '1',
                    phoneNumber: ''
                });
                this.form.clear();
            },

            deletePhone(index) {
                this.form.phone.splice(index, 1);
            },

            addFax() {
                this.form.fax.push({
                    phonePrefix: '1',
                    phoneNumber: ''
                });
                this.form.clear();
            },

            deleteFax(index) {
                this.form.fax.splice(index, 1);
            },

            loadPaginateEmergency(url) {
                console.log(url)
                this.url = url;
                this.loadEmergency();
            },

            loadEmergency() {
                axios.get('/api/emergency').then(({data}) => (this.emergency = data, console.log(data)));
            },

            modalForNewEmergency() {
                {
                    this.editEmergency = false;
                    this.form.reset();
                    $('#EmergencyEditModal').modal('show');
                }
            },

            modalForChangeEmergency(Emergency) {
                let prev_group = JSON.parse(JSON.stringify(Emergency));
                this.editEmergency = true;
                this.form.reset();
                $('#EmergencyEditModal').modal('show');
                this.form.fill(prev_group);
                if (this.form.phone == []) {
                    this.form.phone = [];
                }
                if (this.form.email == []) {
                    this.form.email = [];
                }
                if (this.form.ip_phone == []) {
                    this.form.ip_phone = [];
                }
            },

            createEmergency() {
                this.$Progress.start()
                this.form.post('/api/emergency')
                    .then(() => {
                        Fire.$emit('UpdateEmergencyTable');
                        $('#EmergencyEditModal').modal('hide');
                        toast({
                            type: 'success',
                            title: 'Служба добавлена'
                        })
                        this.$Progress.finish();
                    })
                    .catch(() => {

                    })
            },

            deleteEmergency(id, name) {
                swal({
                    title: 'Вы уверены?',
                    text: "После удаления будет невозможно восстановить данный элемент",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Удалить',
                    cancelButtonText: 'Отмена'
                }).then((result) => {

                        if (result.value) {
                            this.form.delete('/api/emergency/' + id).then(() => {
                                swal(
                                    'Служба ' + name + ' была удалена'
                                )
                                Fire.$emit('UpdateEmergencyTable');

                            }).catch(() => {
                                swal(
                                    'Служба ' + name + ' не была удалена'
                                )

                            })
                        }
                    }
                )
            },

            updateEmergency(id) {
                this.$Progress.start();
                this.form.put('/api/emergency/' + this.form.id)
                    .then(() => {
                        toast({
                            type: 'success',
                            title: 'Пользователь обновлен'
                        });
                        $('#EmergencyEditModal').modal('hide');
                        Fire.$emit('UpdateEmergencyTable');
                        this.$Progress.finish();
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    })
            },
        },
        created() {
            this.loadEmergency();
            Fire.$on('UpdateEmergencyTable', () => {
                this.loadEmergency();
            });
        }
    }
</script>

<style scoped>
    .modal-dialog {
        overflow-y: scroll !important;
    }

    .modal-open .modal {
        overflow-y: scroll !important;
    }

    .modal-content {
        overflow-y: scroll !important;
    }

    .under-div {
        background-color: #fff;
        padding: 1rem;
        border-bottom: 1px solid #ced4da;
    }

    .modal-content {
        background-color: transparent;
    }

    .modal-body {
        background-color: transparent;
        padding: 0;
    }

    .modal-header {
        background-color: #fff;
        padding: 1rem;
    }

    .modal-footer {
        background-color: #fff;
        padding: 1rem;
    }

    .custom-search {
        outline: none !important;
        box-shadow: none;
    }
</style>
