import { useState, useRef, useEffect } from "react";
import { NavLink, Outlet, useNavigate } from "react-router-dom";
import { BarChart3, Radio, Shield, Layout, Video, HelpCircle, Menu, X, User, LogOut, ChevronDown } from "lucide-react";
import logo from "@/assets/logo.png";
import SubscriptionBadge from "@/components/SubscriptionBadge";

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
  const [profileOpen, setProfileOpen] = useState(false);
  const [isImpersonating, setIsImpersonating] = useState(false);
  const profileRef = useRef<HTMLDivElement>(null);
  const navigate = useNavigate();

  useEffect(() => {
    fetch('/api/me', {
      credentials: 'include',
      headers: { 'Accept': 'application/json' }
    })
      .then(res => {
        if (!res.ok) {
          localStorage.removeItem("isAuthenticated");
          localStorage.removeItem("userEmail");
          navigate("/login");
        } else {
          return res.json();
        }
      })
      .then(data => {
        if (data?.data?.is_impersonating) {
          setIsImpersonating(true);
        }
      })
      .catch(() => {
        localStorage.removeItem("isAuthenticated");
        localStorage.removeItem("userEmail");
        navigate("/login");
      });
  }, [navigate]);

  useEffect(() => {
    const handler = (e: MouseEvent) => {
      if (profileRef.current && !profileRef.current.contains(e.target as Node)) {
        setProfileOpen(false);
      }
    };
    document.addEventListener("mousedown", handler);
    return () => document.removeEventListener("mousedown", handler);
  }, []);

  const handleLogout = async () => {
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
    navigate("/login");
  };

  const sidebarContent = (mobile = false) => (
    <>
      <NavLink to="/dashboard/progress" onClick={() => mobile && setMobileOpen(false)} className="px-5 py-5 flex items-center gap-3 no-underline">
        <img src={logo} alt="PS Logo" className="w-10 h-10 object-contain" />
        <span className="text-base font-bold tracking-wide" style={{ color: "hsl(270 75% 75%)" }}>
          PROFIT<span style={{ color: "hsl(90 85% 55%)" }}>STREAM</span>
        </span>
      </NavLink>
      <div className="text-[10px] uppercase tracking-widest px-5 pb-3 font-semibold" style={{ color: "hsl(270 15% 35%)" }}>
        Platform
      </div>
      <nav className="flex flex-col gap-1 px-3 flex-1">
        {navItems.map((item) => (
          <NavLink
            key={item.path}
            to={item.path}
            onClick={() => mobile && setMobileOpen(false)}
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
      {mobile && (
        <div className="px-3 py-4 mt-auto" style={{ borderTop: "1px solid hsl(270 35% 20% / 0.4)" }}>
          <button
            onClick={() => { setMobileOpen(false); navigate("/dashboard/profile"); }}
            className="flex items-center gap-3 w-full px-3 py-2.5 rounded-lg text-sm font-medium transition-colors hover:bg-[hsl(270_35%_18%/0.3)]"
            style={{ color: "hsl(260 15% 70%)" }}
          >
            <div className="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-bold"
              style={{ background: "linear-gradient(135deg, hsl(270 75% 50%), hsl(90 85% 40%))", color: "#fff" }}>
              SP
            </div>
            <div className="flex-1 text-left">
              <div className="text-sm font-medium" style={{ color: "hsl(260 15% 80%)" }}>StreamerPro</div>
              <div className="text-[10px] mt-0.5"><SubscriptionBadge tier="lite" size="sm" /></div>
            </div>
          </button>
          <button
            onClick={() => { setMobileOpen(false); handleLogout(); }}
            className="flex items-center gap-3 w-full px-3 py-2.5 rounded-lg text-sm font-medium transition-colors hover:bg-[hsl(0_75%_50%/0.1)] mt-1"
            style={{ color: "hsl(0 75% 60%)" }}
          >
            <LogOut size={17} />
            <span>Выйти</span>
          </button>
        </div>
      )}
    </>
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
        {sidebarContent(false)}
      </aside>

      {/* Main content */}
      <div className="flex-1 flex flex-col overflow-hidden relative z-10">
        {/* Top header with profile */}
        <header className="hidden md:flex items-center justify-between px-6 py-3 flex-shrink-0 relative z-50" style={{
          background: "hsl(270 45% 6% / 0.5)",
          borderBottom: "1px solid hsl(270 35% 20% / 0.3)",
          backdropFilter: "blur(12px)",
        }}>
          {isImpersonating && (
            <button
              onClick={async () => {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                await fetch('/admin/stop-impersonate', {
                  method: 'POST',
                  credentials: 'include',
                  headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                  }
                });
                window.location.href = '/admin/users';
              }}
              className="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all"
              style={{
                background: "linear-gradient(135deg, hsl(270 75% 50%), hsl(90 85% 40%))",
                color: "#fff",
                border: "1px solid hsl(270 75% 55% / 0.5)",
                boxShadow: "0 0 20px hsl(270 75% 55% / 0.3)",
              }}
            >
              <LogOut size={16} />
              Вернуться в админку
            </button>
          )}
          {!isImpersonating && <div />}
          <div ref={profileRef} className="relative">
            <button
              onClick={() => setProfileOpen(!profileOpen)}
              className="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition-all duration-200 hover:bg-[hsl(270_35%_18%/0.4)]"
              style={{
                background: profileOpen ? "hsl(270 35% 18% / 0.4)" : "transparent",
                border: "1px solid hsl(270 35% 20% / 0.3)",
              }}
            >
              <div className="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-bold"
                style={{ background: "linear-gradient(135deg, hsl(270 75% 50%), hsl(90 85% 40%))", color: "#fff" }}>
                SP
              </div>
              <span className="text-sm font-medium" style={{ color: "hsl(260 15% 80%)" }}>StreamerPro</span>
              <ChevronDown size={14} style={{ color: "hsl(260 15% 55%)", transform: profileOpen ? "rotate(180deg)" : "none", transition: "transform 0.2s" }} />
            </button>

            {profileOpen && (
              <div className="absolute right-0 top-full mt-2 w-52 rounded-xl overflow-hidden shadow-2xl z-50" style={{
                background: "linear-gradient(180deg, hsl(270 40% 12%), hsl(270 35% 8%))",
                border: "1px solid hsl(270 35% 22% / 0.6)",
              }}>
                <div className="px-4 py-3" style={{ borderBottom: "1px solid hsl(270 35% 20% / 0.4)" }}>
                  <div className="text-sm font-semibold" style={{ color: "hsl(260 15% 85%)" }}>StreamerPro</div>
                  <div className="text-[11px]" style={{ color: "hsl(260 15% 50%)" }}>streamer@example.com</div>
                  <div className="mt-1.5"><SubscriptionBadge tier="lite" size="sm" /></div>
                </div>
                <div className="py-1.5">
                  <button
                    onClick={() => { setProfileOpen(false); navigate("/dashboard/profile"); }}
                    className="flex items-center gap-2.5 w-full px-4 py-2 text-sm transition-colors hover:bg-[hsl(270_35%_20%/0.4)]"
                    style={{ color: "hsl(260 15% 70%)" }}
                  >
                    <User size={15} />
                    Мой профиль
                  </button>
                </div>
                <div style={{ borderTop: "1px solid hsl(270 35% 20% / 0.4)" }} className="py-1.5">
                  <button
                    onClick={() => { setProfileOpen(false); handleLogout(); }}
                    className="flex items-center gap-2.5 w-full px-4 py-2 text-sm transition-colors hover:bg-[hsl(0_75%_50%/0.1)]"
                    style={{ color: "hsl(0 75% 60%)" }}
                  >
                    <LogOut size={15} />
                    Выйти
                  </button>
                </div>
              </div>
            )}
          </div>
        </header>

        <main className="flex-1 overflow-auto pt-14 md:pt-0">
          <Outlet />
        </main>
      </div>
    </div>
  );
};

export default DashboardLayout;
