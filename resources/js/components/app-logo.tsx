import AppLogoIcon from './app-logo-icon';

export default function AppLogo() {
    return (
        <>
            <div className="bg-sidebar-primary text-sidebar-primary-foreground flex aspect-square size-8 items-center justify-center rounded-md">
                <AppLogoIcon className="size-5 fill-current text-white dark:text-black" />
            </div>
            <div className="ml-0 grid flex-1 text-left text-sm">
                <span className="mb-1 truncate leading-none font-semibold">Polytechnic Student</span>
                <span className="mb-0.5 truncate leading-none font-semibold">Assist</span>
            </div>
        </>
    );
}
