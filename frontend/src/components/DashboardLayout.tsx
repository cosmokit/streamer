import { useState } from "react";
import { NavLink, Outlet, useNavigate } from "react-router-dom";
import { BarChart3, Radio, Shield, Layout, Video, HelpCircle, Menu, X, User, LogOut } from "lucide-react";
import logo from "@/assets/logo.png";

const navItems = [
  { icon: BarChart3, label: "Мой прогресс", path: "/dashboard/progress" },
  { icon: Radio, label: "Управление трафиком", path: "/dashboard/traffic" },
  { icon: Shield, label: "Мои прокси", path: "/dashboard/proxy" },
  { icon: Layout, label: "Шаблоны", path: "/dashboard/templates" },
  { icon: Video, label: "Записи", path: "/dashboard/records" },
  { icon: HelpCircle, label: "Помощь", path: "/dashboard/help" },
];

const DashboardLayout = () => {
  const [mobileOpen, setMobileOpen] = useState(false);
  const navigate = useNavigate();

  const sidebarContent = (
    <div className="flex flex-col h-full">
      <NavLink to="/" className="px-5 py-5 flex items-center gap-3 hover:opacity-80 transition-opacity cursor-pointer" onClick={() => setMobileOpen(false)}>
        <img src={logo} alt="PS Logo" className="w-10 h-10 object-contain" />
        <span className="text-base font-bold tracking-wide" style={{ color: "hsl(270 75% 75%)" }}>
          PROFIT<span style={{ color: "hsl(90 85% 55%)" }}>STREAM</span>
        </span>
      </NavLink>
      {/* Profile at top */}
      <NavLink
        to="/dashboard/profile"
        onClick={() => setMobileOpen(false)}
        className="flex items-center gap-3 mx-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-[hsl(270_35%_18%/0.3)] mb-3"
        style={({ isActive }) => ({
          background: isActive
            ? "linear-gradient(135deg, hsl(270 75% 50% / 0.25), hsl(90 85% 40% / 0.1))"
            : "hsl(270 25% 12% / 0.5)",
          color: isActive ? "hsl(90 85% 65%)" : "hsl(260 15% 75%)",
          border: `1px solid ${isActive ? "hsl(270 45% 28% / 0.5)" : "hsl(270 35% 18% / 0.3)"}`,
        })}
      >
        <div className="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-bold"
          style={{ background: "linear-gradient(135deg, hsl(270 75% 50%), hsl(90 85% 40%))", color: "#fff" }}>
          SP
        </div>
        <div className="flex flex-col">
          <span className="text-sm font-semibold">StreamerPro</span>
          <span className="text-[10px]" style={{ color: "hsl(var(--muted-foreground))" }}>PRO аккаунт</span>
        </div>
      </NavLink>
      <div className="text-[10px] uppercase tracking-widest px-5 pb-3 font-semibold" style={{ color: "hsl(270 15% 35%)" }}>
        Platform
      </div>
      <nav className="flex flex-col gap-1 px-3 flex-1 min-h-0 overflow-y-auto">
        {navItems.map((item) => (
          <NavLink
            key={item.path}
            to={item.path}
            onClick={() => setMobileOpen(false)}
            className={({ isActive }) =>
              `flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 ${
                isActive ? "" : "hover:bg-[hsl(270_35%_18%/0.3)]"
              }`
            }
            style={({ isActive }) => ({
              background: isActive
                ? "linear-gradient(135deg, hsl(270 75% 50% / 0.25), hsl(90 85% 40% / 0.1))"
                : "transparent",
              color: isActive ? "hsl(90 85% 65%)" : "hsl(260 15% 55%)",
              borderLeft: isActive ? "2px solid hsl(90 85% 50%)" : "2px solid transparent",
              boxShadow: isActive ? "0 0 15px hsl(270 75% 55% / 0.1)" : "none",
            })}
          >
            <item.icon size={17} />
            <span>{item.label}</span>
          </NavLink>
        ))}
      </nav>
      <div className="mx-5 mb-4 mt-4 flex-shrink-0">
        <div className="h-px mb-4" style={{
          background: "linear-gradient(90deg, transparent, hsl(90 85% 45% / 0.3), hsl(270 75% 50% / 0.3), transparent)"
        }} />
        <button
          onClick={async () => {
            try {
              await fetch('/api/logout', {
                method: 'POST',
                credentials: 'include',
              });
            } catch (error) {
              console.error('Logout error:', error);
            }
            localStorage.removeItem("isAuthenticated");
            localStorage.removeItem("userEmail");
            setMobileOpen(false);
            navigate("/login");
          }}
          className="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-[hsl(0_75%_50%/0.1)] w-full"
          style={{ color: "hsl(0 75% 60%)" }}
        >
          <LogOut size={17} />
          <span>Выйти</span>
        </button>
      </div>
    </div>
  );

  return (
    <div className="flex min-h-screen w-full gradient-bg relative overflow-hidden">
      {/* Background effects */}
      <div className="absolute inset-0 pointer-events-none">
        <div className="absolute inset-0" style={{
          background: "linear-gradient(125deg, transparent 20%, hsl(270 75% 55% / 0.04) 40%, transparent 60%)"
        }} />
        <div className="absolute inset-0" style={{
          background: "linear-gradient(200deg, transparent 30%, hsl(90 85% 45% / 0.03) 50%, transparent 70%)"
        }} />
        <div className="absolute -top-32 left-1/2 -translate-x-1/2 w-[600px] h-[300px] rounded-full" style={{
          background: "radial-gradient(ellipse, hsl(270 75% 45% / 0.08), transparent 70%)"
        }} />
        {[...Array(20)].map((_, i) => (
          <div key={i} className="absolute rounded-full" style={{
            width: 1.5 + (i % 3),
            height: 1.5 + (i % 3),
            top: `${8 + (i * 4.7) % 84}%`,
            left: `${3 + (i * 7.3) % 94}%`,
            background: i % 3 === 0 ? "hsl(270 75% 65%)" : i % 3 === 1 ? "hsl(90 85% 55%)" : "hsl(195 100% 60%)",
            opacity: 0.15 + (i % 5) * 0.06,
          }} />
        ))}
      </div>

      {/* Mobile header */}
      <div className="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-4 py-3 md:hidden" style={{
        background: "linear-gradient(180deg, hsl(270 45% 8% / 0.95), hsl(270 35% 5% / 0.9))",
        borderBottom: "1px solid hsl(270 35% 20% / 0.5)",
        backdropFilter: "blur(16px)",
      }}>
        <div className="flex items-center gap-2.5">
          <img src={logo} alt="PS Logo" className="w-8 h-8 object-contain" />
          <span className="text-sm font-bold tracking-wide" style={{ color: "hsl(270 75% 75%)" }}>
            PROFIT<span style={{ color: "hsl(90 85% 55%)" }}>STREAM</span>
          </span>
        </div>
        <button
          onClick={() => setMobileOpen(!mobileOpen)}
          className="w-9 h-9 flex items-center justify-center rounded-lg transition-colors"
          style={{
            background: mobileOpen ? "hsl(270 75% 50% / 0.2)" : "hsl(270 25% 14%)",
            color: "hsl(260 20% 85%)",
            border: "1px solid hsl(270 25% 22%)",
          }}
        >
          {mobileOpen ? <X size={18} /> : <Menu size={18} />}
        </button>
      </div>

      {/* Mobile overlay */}
      {mobileOpen && (
        <div className="fixed inset-0 z-40 md:hidden" onClick={() => setMobileOpen(false)}
          style={{ background: "hsl(0 0% 0% / 0.6)", backdropFilter: "blur(4px)" }}
        />
      )}

      {/* Mobile drawer */}
      <aside
        className={`fixed top-0 left-0 bottom-0 z-40 w-64 flex flex-col md:hidden transition-transform duration-300 ease-out ${
          mobileOpen ? "translate-x-0" : "-translate-x-full"
        }`}
        style={{
          background: "linear-gradient(180deg, hsl(270 45% 8% / 0.98), hsl(270 35% 5% / 0.99))",
          borderRight: "1px solid hsl(270 35% 20% / 0.5)",
          backdropFilter: "blur(20px)",
          paddingTop: "60px",
        }}
      >
        {sidebarContent}
      </aside>

      {/* Desktop sidebar */}
      <aside className="w-56 flex-shrink-0 hidden md:flex flex-col relative z-10" style={{
        background: "linear-gradient(180deg, hsl(270 45% 8% / 0.9), hsl(270 35% 5% / 0.95))",
        borderRight: "1px solid hsl(270 35% 20% / 0.5)",
        backdropFilter: "blur(16px)",
      }}>
        {sidebarContent}
      </aside>

      {/* Main content */}
      <main className="flex-1 overflow-auto relative z-10 pt-14 md:pt-0">
        <Outlet />
      </main>
    </div>
  );
};

export default DashboardLayout;
