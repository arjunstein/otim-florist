export interface Stat {
    label: string;
    value: string;
    delta: string;
    up: boolean;
}

export interface RecentProduct {
    id: number;
    name: string;
    imageUrl: string | null;
    categoryName: string;
    price: number;
    salePrice: number | null;
}

export interface CategorySummary {
    id: number;
    name: string;
    productCount: number;
}

export interface MostClickedProduct {
    id: number;
    name: string;
    imageUrl: string | null;
    categoryName: string;
    clickCount: number;
}

export interface DashboardProps {
    stats: Stat[];
    recentProducts: RecentProduct[];
    categorySummary: CategorySummary[];
    mostClickedProducts: MostClickedProduct[];
}

export interface Product {
    id: number;
    name: string;
    description: string | null;
    imageUrl: string | null;
    category: ProductCategory;
    price: number;
    salePrice: number | null;
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

export interface StoreInfo {
    name: string;
    phone: string;
    address: string;
    hours: string;
}

export interface SettingsProps {
    store: StoreInfo;
}

