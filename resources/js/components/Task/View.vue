<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { useToast } from '@/components/ui/toast/use-toast';
import { useTaskStore } from '@/lib/stores/useTaskStore';
import { CreateTaskRequest } from '@/types/CreateTaskRequest';
import { UpdateTaskRequest } from '@/types/UpdateTaskRequest';
import { useQueryClient } from '@tanstack/vue-query';
import { isAxiosError } from 'axios';
import { storeToRefs } from 'pinia';
import { computed, ref, watch } from 'vue';
import { statuses } from './statuses';

const store = useTaskStore();
const { task, isEditing, isViewing } = storeToRefs(store);

const status = computed(() => statuses.find(({ value }) => value === task.value?.status));

const queryClient = useQueryClient();

const handleDelete = async () => {
    if (!task.value) {
        return;
    }

    await store.delete(task.value);

    await queryClient.invalidateQueries({ queryKey: ['tasks'] });
};

const editData = ref<UpdateTaskRequest>({
    title: '',
    description: null,
    status: 'PENDING',
});

watch(task, () => {
    editData.value.title = task.value?.title ?? '';
    editData.value.description = task.value?.description;
    editData.value.status = task.value?.status ?? 'PENDING';
});

const { toast } = useToast();

const handleSave = async () => {
    const action = task.value ? () => store.update(editData.value) : () => store.create(editData.value as CreateTaskRequest);

    try {
        await action();

        toast({
            title: 'Saved successfully!',
            variant: 'success',
            duration: 2000,
        });
    } catch (e) {
        let message = 'An unknown error occurred';

        if (isAxiosError(e)) {
            message = e.response?.data?.message ?? e.message;
        } else if (e instanceof Error) {
            message = e.message;
        }

        toast({
            title: 'Oops... Something went wrong!',
            description: message,
            variant: 'destructive',
        });
    }

    await queryClient.invalidateQueries({ queryKey: ['tasks'] });
};
</script>

<template>
    <Dialog :open="isViewing" @update:open="(val) => !val && store.close()">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>
                    <template v-if="isEditing && task">Edit task</template>
                    <template v-else-if="isEditing">Create task</template>
                    <template v-else>Details</template>
                </DialogTitle>
            </DialogHeader>

            <div class="space-y-4">
                <div class="flex flex-col justify-between gap-3 sm:flex-row">
                    <div class="w-auto sm:w-2/3">
                        <Label for="title">Title</Label>

                        <Input v-if="isEditing" id="title" v-model="editData.title" class="mt-1" />

                        <p v-else class="text-muted-foreground">{{ task?.title }}</p>
                    </div>

                    <div class="w-auto sm:w-1/3">
                        <Label for="status">Status</Label>

                        <Select v-if="isEditing" v-model="editData.status" :defaultValue="statuses[0].value">
                            <SelectTrigger class="mt-1">
                                <SelectValue placeholder="Select a status" />
                            </SelectTrigger>

                            <SelectContent>
                                <template v-for="status in statuses" v-bind:key="status.value">
                                    <SelectItem :value="status.value" class="cursor-pointer">
                                        <div class="flex w-24 gap-2">
                                            {{ status.label }}
                                            <component :is="status?.icon" class="h-4 w-4" />
                                        </div>
                                    </SelectItem>
                                </template>
                            </SelectContent>
                        </Select>

                        <p v-else class="flex items-center gap-1 text-muted-foreground">
                            <component :is="status?.icon" />
                            {{ status?.label }}
                        </p>
                    </div>
                </div>

                <div>
                    <Label for="description">Description</Label>

                    <Textarea v-if="isEditing" id="description" v-model="editData.description" class="mt-1 h-36" />

                    <p v-else class="whitespace-pre-wrap text-muted-foreground">
                        {{ task?.description || '—' }}
                    </p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-2">
                <p v-if="task?.created_at" class="mr-auto text-sm text-muted-foreground">
                    Created at {{ new Date(task.created_at).toLocaleString() }}
                </p>

                <Button v-if="!isEditing" @click="isEditing = true" variant="secondary"> Edit </Button>

                <Button v-if="!isEditing" @click="handleDelete" variant="destructive"> Delete </Button>

                <template v-else>
                    <Button @click="task ? (isEditing = false) : store.close()" variant="outline">Cancel</Button>
                    <Button @click="handleSave" :disabled="!editData.title || !editData.status">Save</Button>
                </template>
            </div>
        </DialogContent>
    </Dialog>
</template>
