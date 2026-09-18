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
    category: string;
    price: string;
    stock: number;
}

export interface ProductsProps {
    products: Product[];
    categories: string[];
}

export interface Category {
    id: number;
    name: string;
    createdAt: string;
}

export interface CategoriesProps {
    categories: Category[];
}

export interface SettingsProps {
    store: {
        name: string;
        phone: string;
        address: string;
        hours: string;
    };
}
