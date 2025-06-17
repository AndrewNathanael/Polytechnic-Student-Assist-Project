import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { Textarea } from "@/components/ui/textarea";
import { Button } from "@/components/ui/button";
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Terminal } from 'lucide-react';
import { Switch } from "@/components/ui/switch";
import { format } from "date-fns";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create a new complaints',
        href: '/complaints/create',
    },

];

// Define the form data type
interface ComplaintForm {
    title: string;
    type: string;
    date: string;
    image: File | null;
    description: string;
    is_anonymous: boolean;
    [key: string]: string | File | boolean | null;
}

export default function Create() {
    const { data, setData, post, processing, errors } = useForm<ComplaintForm>({
        title: '',
        type: '',
        date: format(new Date(), 'yyyy-MM-dd'), // Set default to today's date
        image: null,
        description: '',
        is_anonymous: false,
    });

    // Add this function to handle date changes
    const handleDateChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const date = e.target.value;
        setData('date', format(new Date(date), 'yyyy-MM-dd'));
    };

const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    post(route('complaints.add'));
};

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create a new complaints" />
            <div className='w-8/16 m-4'>
                <form onSubmit={handleSubmit} encType="multipart/form-data">

                    {Object.keys(errors).length > 0 && (
                        <Alert>
                            <Terminal className="h-4 w-4" />
                            <AlertTitle>Erorrs!</AlertTitle>
                            <AlertDescription>
                                <ul>
                                    {Object.entries(errors).map(([key, masage]) => (
                                        <li key={key} className="text-red-500 text-sm">
                                            {masage as string}
                                        </li>
                                    ))}
                                </ul>
                            </AlertDescription>
                        </Alert>
                    )}

                    <div>
                        <Label htmlFor="title">Title</Label>
                        <Input
                            placeholder="Title"
                            value={data.title}
                            onChange={(e) => setData('title', e.target.value)}
                        />
                    </div>
                    <div className='mt-2'>
                        <Label htmlFor="type">Complaint Type</Label>
                        <Select
                            value={data.type}
                            onValueChange={(value) => setData('type', value)}
                        >
                            <SelectTrigger className="w-full">
                                <SelectValue placeholder="Select complaint type" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="academic">Academic</SelectItem>
                                <SelectItem value="administrative">Administrative</SelectItem>
                                <SelectItem value="financial">Financial</SelectItem>
                                <SelectItem value="facility">Facility</SelectItem>
                                <SelectItem value="lecturer">Lecturer</SelectItem>
                                <SelectItem value="student">Student</SelectItem>
                                <SelectItem value="other">Other</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <div className='mt-2'>
                        <Label htmlFor="date">Date</Label>
                        <Input
                            type="date"
                            id="date"
                            value={data.date}
                            onChange={handleDateChange}
                            max={format(new Date(), 'yyyy-MM-dd')} // Prevent future dates
                        />
                        {errors.date && <p className="text-red-500 text-sm">{errors.date}</p>}
                    </div>
                    <div className='mt-2'>
                        <Label htmlFor="image">Image</Label>
                        <Input
                            type="file"
                            id="image"
                            accept="image/*"
                            onChange={(e: React.ChangeEvent<HTMLInputElement>) => {
                                const file = e.target.files?.[0] || null;
                                setData('image', file);
                            }}
                        />
                        {errors.image && <p className="text-red-500 text-sm">{errors.image}</p>}
                    </div>

                    <div className='mt-2'>
                        <Label htmlFor="description">Description</Label>
                        <Textarea
                            id="description"
                            placeholder="Enter your complaint description here..."
                            value={data.description}
                            onChange={(e) => setData('description', e.target.value)}
                            className="min-h-[150px]"
                        />
                        {errors.description && <p className="text-red-500 text-sm">{errors.description}</p>}
                    </div>

                    <div className="flex items-center space-x-2 mt-4">
                        <Switch
                            id="is_anonymous"
                            checked={data.is_anonymous}
                            onCheckedChange={(checked) => setData('is_anonymous', checked)}
                        />
                        <Label htmlFor="is_anonymous">
                            Submit Anonymously
                        </Label>
                    </div>
                    <p className="text-sm text-gray-500 mt-1">
                        If enabled, your name will not be shown to admin/officers
                    </p>

                    <Button
                        type="submit"
                        className="mt-4 w-full"
                        disabled={processing}
                    >
                        {processing ? 'Submitting...' : 'Submit Complaint'}
                    </Button>
                </form>
            </div>
        </AppLayout>
    );
}
