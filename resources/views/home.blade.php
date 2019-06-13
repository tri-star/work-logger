@extends('layouts.app')

@section('content')
<div class="">
    <v-layout >
        <v-flex xs6 md4 text-xs-center>
            <v-card>
                <v-toolbar dark color="secondary">タスク</v-toolbar>
                <v-card-text class="display-1">0</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn @click="openAddTaskDialog">追加</v-btn>
                </v-card-actions>
            </v-card>
        </v-flex>
    </v-layout>
</div>
<add-task-dialog ref="AddTaskDialog"></add-task-dialog>

@stop
