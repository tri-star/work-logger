<template>
    <WlModal :showModal="showModal">
        <template v-slot:body>
            <section>
                <label class="section-title" for="update-type-1">
                    <input
                        id="update-type-1"
                        name="update_type"
                        type="radio"
                        value="1"
                        v-model="updateType"
                    />
                    指定日数分相対的にずらす
                </label>

                <div class="form">
                    <div class="row">
                        <div class="col-header">基準日</div>
                        <div class="col">
                            <input
                                type="number"
                                name="offset_date"
                                class="text-box offset-date"
                                v-model="offsetDate"
                                :disabled="updateType != 1"
                            />日
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <label class="section-title" for="update-type-2">
                    <input
                        id="update-type-2"
                        name="update_type"
                        type="radio"
                        value="2"
                        v-model="updateType"
                    />

                    開始日・終了日を直接指定</label
                >

                <div class="form">
                    <div class="row">
                        <div class="col-header">開始日</div>
                        <div class="col">
                            <WlDate
                                name="start_date"
                                v-model="startDate"
                                class="text-box date"
                                :disabled="updateType != 2"
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-header">終了日</div>
                        <div class="col">
                            <WlDate
                                name="end_date"
                                v-model="endDate"
                                class="text-box date"
                                :disabled="updateType != 2"
                            />
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="form">
                    <div class="row">
                        <div class="">
                            <button class="button" @click="handleSubmit">
                                更新
                            </button>
                            <button class="button" @click="handleClose">
                                キャンセル
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </template>
    </WlModal>
</template>

<script>
import WlDate from "../../components/form/wl-date"
import WlModal from "../../components/wl-modal"
import adapterFactory from "../../adapters/adapter-factory"

export default {
    props: {},
    components: {
        WlDate,
        WlModal
    },
    data() {
        return {
            showModal: false,
            updateType: 1,
            offsetDate: "",
            startDate: "",
            endDate: "",
            taskIds: []
        }
    },

    methods: {
        open(ids) {
            this.showModal = true
            this.taskIds = ids
        },

        handleSubmit() {
            this.showModal = false

            const TaskAdapter = adapterFactory.get("TaskAdapter")
            TaskAdapter.bulkDateUpdate(this.taskIds, this.updateType, {
                offset_date: this.offsetDate,
                start_date: this.startDate,
                end_date: this.endDate
            })
        },
        handleClose() {
            this.showModal = false
        }
    },

    mounted() {}
}
</script>

<style lang="scss" scoped>
@import "../../../sass/imports";

section {
    display: block;
    margin-bottom: 20px;
}

label {
    display: block;
    background-color: $light-primary-color;
    padding: 5px;
}

.date {
    width: 120px;
}

.offset-date {
    width: 60px;
}

.form {
    width: 300px;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px;

    .row {
        display: flex;
        justify-content: flex-start;
        align-content: center;
        padding: 10px;

        .col-header {
            width: 80px;
        }
        .col {
            width: 200px;
        }
    }
    .button {
        margin-right: 20px;
    }

    .button-area {
        margin-top: 10px;
        justify-self: center;
    }
}

.errors {
    margin-top: 5px;
    background-color: rgba(255, 100, 100, 0.2);
    list-style: none;
    padding: 0;
    li {
        padding: 5px 10px;
        color: #e00;
    }
}
</style>
