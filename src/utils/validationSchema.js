import { object, string, ref } from "yup"

export const changePasswordSchema = object().shape({
    oldPassword: string()
        .min(6, "Invalid old password")
        .max(20, "Invalid old password")
        .required("Old password is required"),

    newPassword: string()
        .min(6, "New password must be at least 6 characters")
        .max(20, "New password must be within 20 characters")
        .required("New password is required"),

    confirmNewPassword: string()
        .required("Please confirm your new password")
        .oneOf([ref("newPassword")], "New password does not match"),
})

export const editAccountSchema = object().shape({
    name: string()
        .trim()
        .min(2, "Name must be at least 2 characters")
        .max(40, "Name must be within 40 characters")
        .required("Name is required"),

    email: string()
        .email("Invalid email")
        .trim()
        .max(40, "Email must be within 40 characters")
        .required("Email is required")
})

export const loginSchema = object().shape({
    email: string().required("Email is required"),

    password: string().required("Password is required")
})

export const registerSchema = object().shape({
    firstName: string()
        .trim()
        .max(40, "First name must be within 40 characters")
        .required("First name is required"),

    lastName: string()
        .trim()
        .max(40, "Last name must be within 40 characters")
        .required("Last name is required"),

    bio: string()
        .trim()
        .max(255, "Bio must be within 255 characters")
        .required("Bio is required"),

    email: string()
        .email("Invalid email")
        .trim()
        .max(40, "Email must be within 40 characters")
        .required("Email is required"),

    work: string()
        .trim()
        .max(40, "Work must be within 40 characters"),

    school: string()
        .trim()
        .max(40, "School must be within 40 characters"),

    college: string()
        .trim()
        .max(40, "College must be within 40 characters"),

    gender: string().required("Please select your gender"),

    currentCity: string()
        .trim()
        .max(40, "Current city must be within 40 characters"),

    homeTown: string()
        .trim()
        .max(40, "Homw town must be within 40 characters"),

    password: string()
        .min(6, "Password must be at least 6 characters")
        .max(20, "Password must be within 20 characters")
        .required("Password is required"),

    confirmPassword: string()
        .required("Please confirm your password")
        .oneOf([ref("password")], "Password does not match"),
})

export const editProfileSchema = object().shape({
    firstName: string()
        .trim()
        .max(40, "First name must be within 40 characters")
        .required("First name is required"),

    lastName: string()
        .trim()
        .max(40, "Last name must be within 40 characters")
        .required("Last name is required"),

    bio: string()
        .trim()
        .max(255, "Bio must be within 255 characters")
        .required("Bio is required"),

    email: string()
        .email("Invalid email")
        .trim()
        .max(40, "Email must be within 40 characters")
        .required("Email is required"),

    work: string()
        .trim()
        .max(40, "Work must be within 40 characters"),

    school: string()
        .trim()
        .max(40, "School must be within 40 characters"),

    college: string()
        .trim()
        .max(40, "College must be within 40 characters"),

    gender: string().required("Please select your gender"),

    currentCity: string()
        .trim()
        .max(40, "Current city must be within 40 characters"),

    homeTown: string()
        .trim()
        .max(40, "Homw town must be within 40 characters")
})