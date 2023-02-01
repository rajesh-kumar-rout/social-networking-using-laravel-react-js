import { Route, Routes } from "react-router-dom"
import Account from "./components/Auth"
import Authenticated from "./components/Authenticated"
import Layout from "./components/Layout"
import NotAuthenticated from "./components/NotAuthenticated"
import EditProfilePage from "./pages/EditProfilePage"
import FollowersPage from "./pages/FollowersPage"
import FollowingPage from "./pages/FollowingsPage"
import HomePage from "./pages/HomePage"
import LoginPage from "./pages/LoginPage"
import ProfileFollowersPage from "./pages/ProfileFollowersPage"
import ProfileIndexPage from "./pages/ProfileIndexPage"
import ProfilePage from "./pages/ProfilePage"
import ProfilePhotosPage from "./pages/ProfilePhotosPage"
import RegisterPage from "./pages/RegisterPage"
import SearchPage from "./pages/SearchPage"

export default function App() {
    return (
        <Routes>
            <Route element={<Account />}>
                <Route element={<Authenticated />}>
                    <Route element={<Layout />}>
                        <Route index element={<HomePage />} />
                        <Route path="/search" element={<SearchPage />} />
                        {/* <Route path="/add-post" element={<AddPostPage />} /> */}
                        {/* <Route path="/auth/profile/:userId" element={<ProfilePage />} /> */}
                        {/* <Route path="/profile/:userId" element={<ProfilePage />} /> */}
                        {/* <Route path="/auth/edit-profile" element={<EditProfilePage />} /> */}
                        <Route path="/auth/edit-profile" element={<EditProfilePage />} />
                        <Route path="/auth/followers" element={<FollowersPage />} />
                        <Route path="/auth/followings" element={<FollowingPage />} />

                        <Route path="/profile/:userId" element={<ProfilePage/>}>
                            <Route index element={<ProfileIndexPage/>}/>
                            <Route path="photos" element={<ProfilePhotosPage/>}/>
                            <Route path="followings" element={<ProfileFollowersPage/>}/>
                        </Route>
                    </Route>
                </Route>




                <Route element={<NotAuthenticated />}>
                    <Route path="/login" element={<LoginPage />} />
                    <Route path="/register" element={<RegisterPage />} />
                </Route>
            </Route>
        </Routes>
    )
}