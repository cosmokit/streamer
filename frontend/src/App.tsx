import { Toaster } from "@/components/ui/toaster";
import { Toaster as Sonner } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import DashboardLayout from "./components/DashboardLayout";
import ProgressPage from "./pages/dashboard/ProgressPage";
import TrafficPage from "./pages/dashboard/TrafficPage";
import ProxyPage from "./pages/dashboard/ProxyPage";
import TemplatesPage from "./pages/dashboard/TemplatesPage";
import RecordsPage from "./pages/dashboard/RecordsPage";
import HelpPage from "./pages/dashboard/HelpPage";
import ProfilePage from "./pages/dashboard/ProfilePage";
import ResetPasswordPage from "./pages/dashboard/ResetPasswordPage";
import LoginPage from "./pages/LoginPage";
import NotFound from "./pages/NotFound";

const queryClient = new QueryClient();

// Check authentication
const isAuthenticated = () => {
  return localStorage.getItem("isAuthenticated") === "true";
};

// Protected Route wrapper
const ProtectedRoute = ({ children }: { children: React.ReactNode }) => {
  return isAuthenticated() ? <>{children}</> : <Navigate to="/login" replace />;
};

const App = () => (
  <QueryClientProvider client={queryClient}>
    <TooltipProvider>
      <Toaster />
      <Sonner />
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<Navigate to="/dashboard/progress" replace />} />
          <Route path="/login" element={<LoginPage />} />
          <Route path="/dashboard" element={
            <ProtectedRoute>
              <DashboardLayout />
            </ProtectedRoute>
          }>
            <Route path="progress" element={<ProgressPage />} />
            <Route path="traffic" element={<TrafficPage />} />
            <Route path="proxy" element={<ProxyPage />} />
            <Route path="templates" element={<TemplatesPage />} />
            <Route path="records" element={<RecordsPage />} />
            <Route path="help" element={<HelpPage />} />
            <Route path="profile" element={<ProfilePage />} />
            <Route path="reset-password" element={<ResetPasswordPage />} />
          </Route>
          <Route path="*" element={<NotFound />} />
        </Routes>
      </BrowserRouter>
    </TooltipProvider>
  </QueryClientProvider>
);

export default App;
