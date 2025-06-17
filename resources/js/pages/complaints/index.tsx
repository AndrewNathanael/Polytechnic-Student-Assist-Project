import { Button } from '@/components/ui/button';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Complaints',
        href: '/complaints',
    },

];

export default function index() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Complaints" />
            <div className='flex flex-col gap-4 m-4'>
                <Link href={route('complaints.create')}><Button>Create a complaint</Button></Link>
            </div>
        </AppLayout>
    );
}
