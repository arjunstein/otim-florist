export interface Stat {
    label: string;
    value: string;
    delta: string;
    up: boolean;
}

export interface SalePoint {
    label: string;
    value: number;
}

export interface Order {
    id: string;
    customer: string;
    item: string;
    total: string;
    status: 'Pending' | 'Arranging' | 'Delivered';
}

export interface LowStock {
    name: string;
    left: number;
}

export interface DashboardProps {
    stats: Stat[];
    sales: SalePoint[];
    orders: Order[];
    lowStock: LowStock[];
}

export interface Product {
    id: number;
    name: string;
    category: ProductCategory;
    price: number;
}

export interface ProductCategory {
    id: number;
    name: string;
}

export interface Pagination {
    currentPage: number;
    lastPage: number;
    perPage: number;
    total: number;
    from: number | null;
    to: number | null;
    nextPageUrl: string | null;
    prevPageUrl: string | null;
}

export interface Paginated<T> {
    data: T[];
    pagination: Pagination;
}

export interface ProductsProps {
    products: Paginated<Product>;
    categories: ProductCategory[];
    filters: {
        search: string | null;
        categoryId: number | null;
    };
}

export interface Category {
    id: number;
    name: string;
    createdAt: string;
}

export interface CategoriesProps {
    categories: Paginated<Category>;
}

export interface SettingsProps {
    store: {
        name: string;
        phone: string;
        address: string;
        hours: string;
    };
}
