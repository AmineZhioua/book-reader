import apiClient from "./apiClient";

// REGISTER PROPS
interface registerProps {
    userData: {
        username: string,
        email: string,
        password: string
    },
    toast: any
};

// USER REGISTER
export async function register({userData, toast}: registerProps) {
    try {
        const registerData = new FormData();

        registerData.append("name", userData.username);
        registerData.append("email", userData.email);
        registerData.append("password", userData.password);

        const registerResponse = await apiClient.post("/api/register", registerData);

        if (registerResponse.status === 201) {
            toast.add({
                severity: 'success',
                summary: "Success",
                detail: "Account created successfull !",
                life: 2000
            });
        }
    } catch(error) {
        console.error("Error in Register: ", error);

        toast.add({
            severity: 'error',
            summary: "Erreur",
            detail: "Une erreur est survenue",
            life: 2000
        });
    }
};