import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { Mail, Lock, Eye, EyeOff, LogIn, UserPlus, ArrowLeft } from "lucide-react";
import { toast } from "@/hooks/use-toast";
import logo from "@/assets/logo.png";

type Mode = "login" | "register" | "forgot";

const LoginPage = () => {
  const navigate = useNavigate();
  const [mode, setMode] = useState<Mode>("login");
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirm, setShowConfirm] = useState(false);
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [forgotSent, setForgotSent] = useState(false);

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!email || !password) {
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
        body: JSON.stringify({ email, password }),
        credentials: 'include',
      });

      const data = await response.json();

      if (response.ok) {
        localStorage.setItem("isAuthenticated", "true");
        localStorage.setItem("userEmail", email);
        toast({ title: "Добро пожаловать!", description: data.message || "Вы успешно вошли в систему" });
        navigate("/dashboard/progress");
      } else {
        toast({ 
          title: "Ошибка авторизации", 
          description: data.message || "Неверный email или пароль", 
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

  const handleRegister = (e: React.FormEvent) => {
    e.preventDefault();
    if (!email || !password || !confirmPassword) {
      toast({ title: "Ошибка", description: "Заполните все поля", variant: "destructive" });
      return;
    }
    if (password !== confirmPassword) {
      toast({ title: "Ошибка", description: "Пароли не совпадают", variant: "destructive" });
      return;
    }
    if (password.length < 6) {
      toast({ title: "Ошибка", description: "Пароль должен содержать минимум 6 символов", variant: "destructive" });
      return;
    }
    // Save auth to localStorage
    localStorage.setItem("isAuthenticated", "true");
    localStorage.setItem("userEmail", email);
    toast({ title: "Регистрация успешна!", description: "Аккаунт создан. Добро пожаловать!" });
    navigate("/dashboard/progress");
  };

  const handleForgot = (e: React.FormEvent) => {
    e.preventDefault();
    if (!email) {
      toast({ title: "Ошибка", description: "Введите email для восстановления", variant: "destructive" });
      return;
    }
    setForgotSent(true);
    toast({ title: "Письмо отправлено", description: `Инструкции отправлены на ${email}` });
  };

  const inputStyle = {
    background: "hsl(var(--input))",
    border: "1px solid hsl(var(--border))",
    color: "hsl(var(--foreground))",
  };

  // Убираем желтый фон автозаполнения
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
      {/* Background effects */}
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
        {/* Logo */}
        <div className="flex items-center justify-center gap-3 mb-8">
          <img src={logo} alt="Logo" className="w-12 h-12 object-contain" />
          <span className="text-xl font-bold tracking-wide" style={{ color: "hsl(270 75% 75%)" }}>
            PROFIT<span style={{ color: "hsl(90 85% 55%)" }}>STREAM</span>
          </span>
        </div>

        <div className="glass-card rounded-xl p-6">
          {mode === "forgot" ? (
            <>
              <button onClick={() => { setMode("login"); setForgotSent(false); }}
                className="flex items-center gap-1.5 text-xs mb-4 transition-colors hover:underline"
                style={{ color: "hsl(var(--muted-foreground))" }}>
                <ArrowLeft size={14} /> Назад к входу
              </button>
              <h1 className="text-xl font-bold mb-1 glow-text" style={{ color: "hsl(var(--foreground))" }}>
                Восстановление пароля
              </h1>
              <p className="text-xs mb-6" style={{ color: "hsl(var(--muted-foreground))" }}>
                Введите email для получения инструкций
              </p>
              {forgotSent ? (
                <div className="text-center py-4">
                  <div className="w-12 h-12 rounded-full mx-auto mb-3 flex items-center justify-center"
                    style={{ background: "hsl(var(--accent) / 0.15)" }}>
                    <Mail size={22} style={{ color: "hsl(var(--accent))" }} />
                  </div>
                  <p className="text-sm font-medium mb-1" style={{ color: "hsl(var(--foreground))" }}>Проверьте почту</p>
                  <p className="text-xs" style={{ color: "hsl(var(--muted-foreground))" }}>
                    Инструкции отправлены на {email}
                  </p>
                  <button onClick={() => { setMode("login"); setForgotSent(false); }}
                    className="glow-btn mt-5 w-full py-2.5 rounded-lg text-sm font-medium"
                    style={{ color: "hsl(var(--primary-foreground))" }}>
                    Вернуться к входу
                  </button>
                </div>
              ) : (
                <form onSubmit={handleForgot} className="flex flex-col gap-4">
                  <div>
                    <label className="text-[11px] font-medium mb-1.5 block" style={{ color: "hsl(var(--muted-foreground))" }}>Email</label>
                    <div className="flex items-center gap-3 px-3.5 py-2.5 rounded-lg" style={inputStyle}>
                      <Mail size={15} style={{ color: "hsl(var(--muted-foreground))" }} />
                      <input className="bg-transparent text-sm flex-1 outline-none" style={{ color: "hsl(var(--foreground))" }}
                        type="email" placeholder="your@email.com" value={email} onChange={e => setEmail(e.target.value)} />
                    </div>
                  </div>
                  <button type="submit" className="glow-btn w-full py-2.5 rounded-lg text-sm font-medium"
                    style={{ color: "hsl(var(--primary-foreground))" }}>
                    Отправить инструкции
                  </button>
                </form>
              )}
            </>
          ) : (
            <>
              <h1 className="text-xl font-bold mb-1 glow-text" style={{ color: "hsl(var(--foreground))" }}>
                {mode === "login" ? "Вход в аккаунт" : "Регистрация"}
              </h1>
              <p className="text-xs mb-6" style={{ color: "hsl(var(--muted-foreground))" }}>
                {mode === "login" ? "Войдите для доступа к платформе" : "Создайте аккаунт для начала работы"}
              </p>
              <form onSubmit={mode === "login" ? handleLogin : handleRegister} className="flex flex-col gap-4">
                <div>
                  <label className="text-[11px] font-medium mb-1.5 block" style={{ color: "hsl(var(--muted-foreground))" }}>Email</label>
                  <div className="flex items-center gap-3 px-3.5 py-2.5 rounded-lg" style={inputStyle}>
                    <Mail size={15} style={{ color: "hsl(var(--muted-foreground))" }} />
                    <input className="bg-transparent text-sm flex-1 outline-none" style={{ color: "hsl(var(--foreground))" }}
                      type="email" placeholder="your@email.com" value={email} onChange={e => setEmail(e.target.value)} />
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
                {mode === "register" && (
                  <div>
                    <label className="text-[11px] font-medium mb-1.5 block" style={{ color: "hsl(var(--muted-foreground))" }}>Подтвердите пароль</label>
                    <div className="flex items-center gap-3 px-3.5 py-2.5 rounded-lg" style={inputStyle}>
                      <Lock size={15} style={{ color: "hsl(var(--muted-foreground))" }} />
                      <input className="bg-transparent text-sm flex-1 outline-none" style={{ color: "hsl(var(--foreground))" }}
                        type={showConfirm ? "text" : "password"} placeholder="••••••••" value={confirmPassword} onChange={e => setConfirmPassword(e.target.value)} />
                      <button type="button" onClick={() => setShowConfirm(!showConfirm)} style={{ color: "hsl(var(--muted-foreground))" }}>
                        {showConfirm ? <EyeOff size={15} /> : <Eye size={15} />}
                      </button>
                    </div>
                  </div>
                )}
                {mode === "login" && (
                  <button type="button" onClick={() => setMode("forgot")}
                    className="text-[11px] text-right transition-colors hover:underline -mt-2"
                    style={{ color: "hsl(var(--primary))" }}>
                    Забыли пароль?
                  </button>
                )}
                <button type="submit" className="glow-btn w-full py-2.5 rounded-lg text-sm font-medium flex items-center justify-center gap-2"
                  style={{ color: "hsl(var(--primary-foreground))" }}>
                  {mode === "login" ? <><LogIn size={15} /> Войти</> : <><UserPlus size={15} /> Зарегистрироваться</>}
                </button>
              </form>
              <div className="mt-5 text-center">
                <span className="text-xs" style={{ color: "hsl(var(--muted-foreground))" }}>
                  {mode === "login" ? "Нет аккаунта? " : "Уже есть аккаунт? "}
                </span>
                <button onClick={() => setMode(mode === "login" ? "register" : "login")}
                  className="text-xs font-medium transition-colors hover:underline"
                  style={{ color: "hsl(var(--primary))" }}>
                  {mode === "login" ? "Зарегистрируйтесь" : "Войдите"}
                </button>
              </div>
            </>
          )}
        </div>
      </div>
    </div>
  );
};

export default LoginPage;
