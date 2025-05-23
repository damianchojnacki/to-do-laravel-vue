export type ValidationErrorResponse = {
    message: string;
    errors: Record<string, string[]>;
};

export type GenericErrorResponse = {
    message: string;
};

export type PaginatedResponse<T> = {
    data: T[];
    meta: {
        current_page: number;
        from: number;
        last_page: number;
        per_page: number;
        to: number;
        total: number;
    };
};
