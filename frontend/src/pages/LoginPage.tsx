import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { User, Lock, Eye, EyeOff, LogIn } from "lucide-react";
import { toast } from "@/hooks/use-toast";
import logo from "@/assets/logo.png";

const LoginPage = () => {
  const navigate = useNavigate();
  const [showPassword, setShowPassword] = useState(false);
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!username || !password) {
      toast({ title: "Ошибка", description: "Заполните все поля", variant: "destructive" });
      return;
    }

    try {
      const response = await fetch('/api/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({ username, password }),
        credentials: 'include',
      });

      const data = await response.json();

      if (response.ok) {
        localStorage.setItem("isAuthenticated", "true");
        toast({ title: "Добро пожаловать!", description: data.message || "Вы успешно вошли в систему" });
        navigate("/dashboard/progress");
      } else {
        toast({ 
          title: "Ошибка авторизации", 
          description: data.message || "Неверный логин или пароль", 
          variant: "destructive" 
        });
      }
    } catch (error) {
      toast({ 
        title: "Ошибка", 
        description: "Не удалось подключиться к серверу", 
        variant: "destructive" 
      });
    }
  };

  const inputStyle = {
    background: "hsl(var(--input))",
    border: "1px solid hsl(var(--border))",
    color: "hsl(var(--foreground))",
  };

  const autoFillStyles = `
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
      -webkit-box-shadow: 0 0 0 30px hsl(var(--input)) inset !important;
      -webkit-text-fill-color: hsl(var(--foreground)) !important;
    }
  `;

  return (
    <div className="min-h-screen gradient-bg flex items-center justify-center relative overflow-hidden px-4">
      <style>{autoFillStyles}</style>
      <div className="absolute inset-0 pointer-events-none">
        <div className="absolute inset-0" style={{
          background: "linear-gradient(125deg, transparent 20%, hsl(270 75% 55% / 0.06) 40%, transparent 60%)"
        }} />
        <div className="absolute -top-32 left-1/2 -translate-x-1/2 w-[600px] h-[300px] rounded-full" style={{
          background: "radial-gradient(ellipse, hsl(270 75% 45% / 0.12), transparent 70%)"
        }} />
        {[...Array(12)].map((_, i) => (
          <div key={i} className="absolute rounded-full" style={{
            width: 1.5 + (i % 3),
            height: 1.5 + (i % 3),
            top: `${8 + (i * 7.3) % 84}%`,
            left: `${3 + (i * 11.3) % 94}%`,
            background: i % 3 === 0 ? "hsl(270 75% 65%)" : i % 3 === 1 ? "hsl(90 85% 55%)" : "hsl(195 100% 60%)",
            opacity: 0.15 + (i % 5) * 0.06,
          }} />
        ))}
      </div>

      <div className="w-full max-w-sm relative z-10">
        <div className="flex items-center justify-center gap-3 mb-8">
          <img src={logo} alt="Logo" className="w-12 h-12 object-contain" />
          <span className="text-xl font-bold tracking-wide" style={{ color: "hsl(270 75% 75%)" }}>
            PROFIT<span style={{ color: "hsl(90 85% 55%)" }}>STREAM</span>
          </span>
        </div>

        <div className="glass-card rounded-xl p-6">
          <h1 className="text-xl font-bold mb-1 glow-text" style={{ color: "hsl(var(--foreground))" }}>
            Вход в аккаунт
          </h1>
          <p className="text-xs mb-6" style={{ color: "hsl(var(--muted-foreground))" }}>
            Войдите для доступа к платформе
          </p>
          <form onSubmit={handleLogin} className="flex flex-col gap-4">
            <div>
              <label className="text-[11px] font-medium mb-1.5 block" style={{ color: "hsl(var(--muted-foreground))" }}>Логин</label>
              <div className="flex items-center gap-3 px-3.5 py-2.5 rounded-lg" style={inputStyle}>
                <User size={15} style={{ color: "hsl(var(--muted-foreground))" }} />
                <input className="bg-transparent text-sm flex-1 outline-none" style={{ color: "hsl(var(--foreground))" }}
                  type="text" placeholder="username" value={username} onChange={e => setUsername(e.target.value)} />
              </div>
            </div>
            <div>
              <label className="text-[11px] font-medium mb-1.5 block" style={{ color: "hsl(var(--muted-foreground))" }}>Пароль</label>
              <div className="flex items-center gap-3 px-3.5 py-2.5 rounded-lg" style={inputStyle}>
                <Lock size={15} style={{ color: "hsl(var(--muted-foreground))" }} />
                <input className="bg-transparent text-sm flex-1 outline-none" style={{ color: "hsl(var(--foreground))" }}
                  type={showPassword ? "text" : "password"} placeholder="••••••••" value={password} onChange={e => setPassword(e.target.value)} />
                <button type="button" onClick={() => setShowPassword(!showPassword)} style={{ color: "hsl(var(--muted-foreground))" }}>
                  {showPassword ? <EyeOff size={15} /> : <Eye size={15} />}
                </button>
              </div>
            </div>
            <button type="submit" className="glow-btn w-full py-2.5 rounded-lg text-sm font-medium flex items-center justify-center gap-2"
              style={{ color: "hsl(var(--primary-foreground))" }}>
              <LogIn size={15} /> Войти
            </button>
          </form>
        </div>
      </div>
    </div>
  );
};

export default LoginPage;
