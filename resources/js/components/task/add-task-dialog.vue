<template>
    <v-dialog v-model="dialog">
        <v-card>
            <v-toolbar dark color="secondary"> タスク新規登録 </v-toolbar>
            <v-card-text>
                <v-text-field v-model="issueNo" label="課題番号"></v-text-field>
                <v-text-field v-model="title" label="タイトル"></v-text-field>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn>登録</v-btn>
                <v-btn @click="dialog = false">キャンセル</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapActions } from "vuex"

export default {
    data() {
        return {
            dialog: false
        }
    },
    computed: {
        issueNo: {
            get() {
                return this.$store.state.AddTask.issueNo
            },
            set(v) {
                this.$store.commit("AddTask/update", {
                    key: "IssueNo",
                    value: v
                })
            }
        },
        title: {
            get() {
                return this.$store.state.AddTask.title
            },
            set(v) {
                this.$store.commit("AddTask/update", {
                    key: "title",
                    value: v
                })
            }
        }
    },
    methods: {
        open() {
            this.dialog = true
        },
        ...mapActions("AddTask", ["create"])
    }
}
</script>
