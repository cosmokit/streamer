import { useState, useEffect } from "react";
import { X, Shield, Upload, Trash2, Circle, CheckCircle } from "lucide-react";
import { toast } from "@/hooks/use-toast";

interface ProxyData {
  id: number;
  host: string;
  port: number;
  username: string | null;
  password: string | null;
  status: 'pending' | 'active' | 'online' | 'offline';
  created_at: string;
}

interface ProxySummary {
  total: number;
  pending: number;
  active: number;
  online: number;
  offline: number;
}

const ProxyPage = () => {
  const [showModal, setShowModal] = useState(false);
  const [proxies, setProxies] = useState<ProxyData[]>([]);
  const [summary, setSummary] = useState<ProxySummary>({ total: 0, pending: 0, active: 0, online: 0, offline: 0 });
  const [loading, setLoading] = useState(true);
  const [uploading, setUploading] = useState(false);
  const [activating, setActivating] = useState(false);
  const [selectedFile, setSelectedFile] = useState<File | null>(null);

  const loadProxies = () => {
    fetch('/api/proxies', {
      credentials: 'include',
      headers: { 'Accept': 'application/json' }
    })
      .then(res => {
        if (!res.ok) {
          return res.json().then(err => Promise.reject(err));
        }
        return res.json();
      })
      .then(data => {
        setProxies(data.data || []);
        setSummary(data.summary || { total: 0, pending: 0, active: 0, online: 0, offline: 0 });
        setLoading(false);
      })
      .catch(err => {
        console.error('Error loading proxies:', err);
        if (err.message === 'Unauthenticated.') {
          window.location.href = '/login';
        }
        setLoading(false);
      });
  };

  useEffect(() => {
    loadProxies();
  }, []);

  const handleUpload = () => {
    if (!selectedFile) {
      toast({ title: "Ошибка", description: "Выберите файл", variant: "destructive" });
      return;
    }

    setUploading(true);
    const formData = new FormData();
    formData.append('file', selectedFile);

    fetch('/api/proxies/upload', {
      method: 'POST',
      credentials: 'include',
      headers: { 'Accept': 'application/json' },
      body: formData
    })
      .then(res => {
        if (!res.ok) {
          return res.json().then(err => Promise.reject(err));
        }
        return res.json();
      })
      .then(data => {
        toast({ title: "Успешно", description: data.message });
        setShowModal(false);
        setSelectedFile(null);
        setUploading(false);
        loadProxies();
      })
      .catch(err => {
        console.error('Error uploading:', err);
        toast({ 
          title: "Ошибка", 
          description: err.message || "Не удалось загрузить файл", 
          variant: "destructive" 
        });
        setUploading(false);
      });
  };

  const handleActivate = () => {
    if (summary.pending === 0) {
      toast({ title: "Ошибка", description: "Нет прокси для активации", variant: "destructive" });
      return;
    }

    setActivating(true);
    fetch('/api/proxies/activate', {
      method: 'POST',
      credentials: 'include',
      headers: { 'Accept': 'application/json' }
    })
      .then(res => {
        if (!res.ok) {
          return res.json().then(err => Promise.reject(err));
        }
        return res.json();
      })
      .then(data => {
        toast({ title: "Успешно", description: data.message });
        setActivating(false);
        loadProxies();
      })
      .catch(err => {
        console.error('Error activating:', err);
        toast({ 
          title: "Ошибка", 
          description: err.message || "Не удалось активировать", 
          variant: "destructive" 
        });
        setActivating(false);
      });
  };

  const handleDelete = (proxyId: number) => {
    if (!confirm('Удалить этот прокси?')) return;

    fetch(`/api/proxies/${proxyId}`, {
      method: 'DELETE',
      credentials: 'include',
      headers: { 'Accept': 'application/json' }
    })
      .then(() => {
        toast({ title: "Удалено", description: "Прокси успешно удалён" });
        loadProxies();
      })
      .catch(err => {
        console.error('Error deleting:', err);
        toast({ title: "Ошибка", description: "Не удалось удалить", variant: "destructive" });
      });
  };

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'online': return 'hsl(90 85% 55%)';
      case 'active': return 'hsl(270 75% 55%)';
      case 'offline': return 'hsl(0 65% 50%)';
      case 'pending': return 'hsl(45 90% 55%)';
      default: return 'hsl(260 15% 50%)';
    }
  };

  const getStatusLabel = (status: string) => {
    switch (status) {
      case 'online': return 'Онлайн';
      case 'active': return 'Активный';
      case 'offline': return 'Офлайн';
      case 'pending': return 'Ожидает';
      default: return status;
    }
  };

  return (
    <div className="p-4 md:p-8 max-w-5xl">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div className="flex items-center gap-3">
          <Shield size={24} style={{ color: "hsl(90 85% 55%)" }} />
          <div>
            <h1 className="text-xl md:text-2xl font-bold glow-text" style={{ color: "hsl(260 20% 93%)" }}>Мои Прокси</h1>
            <p className="text-sm" style={{ color: "hsl(260 15% 50%)" }}>
              Всего: {summary.total} | Ожидают: {summary.pending} | Онлайн: {summary.online} | Офлайн: {summary.offline}
            </p>
          </div>
        </div>
        <div className="flex gap-2">
          {summary.pending > 0 && (
            <button 
              onClick={handleActivate}
              disabled={activating}
              className="px-3 sm:px-5 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-medium text-white glow-btn flex items-center gap-2"
              style={{ background: 'hsl(90 85% 45%)', border: '1px solid hsl(90 85% 55%)' }}
            >
              <CheckCircle size={15} /> <span className="hidden sm:inline">Активировать ({summary.pending})</span><span className="sm:hidden">Активировать</span>
            </button>
          )}
          <button onClick={() => setShowModal(true)} className="px-3 sm:px-5 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-medium text-white glow-btn flex items-center gap-2">
            <Upload size={15} /> <span className="hidden sm:inline">Загрузить Список</span><span className="sm:hidden">Загрузить</span>
          </button>
        </div>
      </div>

      {summary.pending > 0 && (
        <div className="glass-card rounded-xl p-4 mb-4" style={{ background: 'hsl(45 60% 45% / 0.08)', border: '1px solid hsl(45 60% 50% / 0.2)' }}>
          <p className="text-sm" style={{ color: "hsl(45 90% 65%)" }}>
            <strong>{summary.pending}</strong> прокси ожидают активации. Нажмите кнопку "Активировать" или обратитесь к администратору.
          </p>
        </div>
      )}

      {loading ? (
        <div className="glass-card rounded-xl p-8 text-center">
          <p style={{ color: "hsl(260 15% 50%)" }}>Загрузка...</p>
        </div>
      ) : proxies.length === 0 ? (
        <>
          <div className="glass-card rounded-xl p-8 mt-4 min-h-[250px] flex flex-col items-center justify-center relative overflow-hidden" style={{
            borderStyle: "dashed",
            borderColor: "hsl(270 35% 23% / 0.5)",
          }}>
            <div className="absolute inset-0 pointer-events-none" style={{
              background: "radial-gradient(ellipse at center, hsl(90 85% 45% / 0.04), transparent 60%)"
            }} />
            <Shield size={40} className="mb-3" style={{ color: "hsl(270 20% 22%)" }} />
            <h2 className="text-lg font-semibold mb-1" style={{ color: "hsl(260 20% 70%)" }}>Список прокси</h2>
            <p className="text-base font-medium mb-1" style={{ color: "hsl(260 15% 50%)" }}>Прокси не добавлены</p>
            <p className="text-sm" style={{ color: "hsl(260 15% 35%)" }}>Загрузите список прокси для начала работы</p>
          </div>

          <div className="glass-card rounded-xl p-6 mt-8">
            <h3 className="font-semibold mb-1" style={{ color: "hsl(260 20% 90%)" }}>Нужна помощь с активацией прокси?</h3>
            <p className="text-sm mb-4" style={{ color: "hsl(260 15% 50%)" }}>Свяжитесь с технической поддержкой для активации прокси и получения помощи.</p>
            <a href="https://t.me/profitstream_support" target="_blank" rel="noopener noreferrer"
              className="inline-block px-5 py-2.5 rounded-lg text-sm font-medium transition-all"
              style={{ background: "hsl(270 25% 14%)", color: "hsl(260 15% 75%)", border: "1px solid hsl(270 25% 22%)" }}>
              Связаться с поддержкой
            </a>
          </div>
        </>
      ) : (
        <div className="glass-card rounded-xl overflow-hidden">
          <div className="overflow-x-auto">
            <table className="w-full">
              <thead>
                <tr style={{ borderBottom: "1px solid hsl(270 25% 20%)" }}>
                  <th className="text-left py-3 px-4 text-xs font-semibold" style={{ color: "hsl(260 15% 50%)" }}>IP АДРЕС</th>
                  <th className="text-left py-3 px-4 text-xs font-semibold" style={{ color: "hsl(260 15% 50%)" }}>ПОРТ</th>
                  <th className="text-left py-3 px-4 text-xs font-semibold" style={{ color: "hsl(260 15% 50%)" }}>ЛОГИН</th>
                  <th className="text-left py-3 px-4 text-xs font-semibold" style={{ color: "hsl(260 15% 50%)" }}>СТАТУС</th>
                  <th className="text-left py-3 px-4 text-xs font-semibold" style={{ color: "hsl(260 15% 50%)" }}>ДАТА</th>
                  <th className="py-3 px-4"></th>
                </tr>
              </thead>
              <tbody>
                {proxies.map((proxy) => (
                  <tr key={proxy.id} style={{ borderBottom: "1px solid hsl(270 25% 15%)" }}>
                    <td className="py-3 px-4">
                      <code className="text-sm" style={{ color: "hsl(260 20% 85%)" }}>{proxy.host}</code>
                    </td>
                    <td className="py-3 px-4">
                      <code className="text-sm" style={{ color: "hsl(260 20% 85%)" }}>{proxy.port}</code>
                    </td>
                    <td className="py-3 px-4">
                      <span className="text-xs" style={{ color: "hsl(260 15% 60%)" }}>
                        {proxy.username || '—'}
                      </span>
                    </td>
                    <td className="py-3 px-4">
                      <div className="flex items-center gap-2">
                        <Circle size={8} fill={getStatusColor(proxy.status)} style={{ color: getStatusColor(proxy.status) }} />
                        <span className="text-xs font-medium" style={{ color: getStatusColor(proxy.status) }}>
                          {getStatusLabel(proxy.status)}
                        </span>
                      </div>
                    </td>
                    <td className="py-3 px-4">
                      <span className="text-xs" style={{ color: "hsl(260 15% 50%)" }}>
                        {new Date(proxy.created_at).toLocaleDateString('ru-RU')}
                      </span>
                    </td>
                    <td className="py-3 px-4 text-right">
                      <button 
                        onClick={() => handleDelete(proxy.id)}
                        className="p-2 rounded-lg transition-all hover:bg-red-500/20"
                        style={{ color: "hsl(0 65% 50%)" }}
                      >
                        <Trash2 size={16} />
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {showModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center" style={{ background: "hsl(270 50% 3% / 0.85)", backdropFilter: "blur(8px)" }}>
          <div className="glass-card rounded-xl p-6 w-full max-w-md relative" style={{
            boxShadow: "0 0 40px hsl(270 75% 45% / 0.15), 0 25px 50px hsl(270 50% 3% / 0.5)"
          }}>
            <button onClick={() => setShowModal(false)} className="absolute top-4 right-4 transition-colors" style={{ color: "hsl(260 15% 50%)" }}>
              <X size={20} />
            </button>
            <h2 className="text-lg font-bold mb-1 glow-text" style={{ color: "hsl(260 20% 93%)" }}>Загрузить Список Прокси</h2>
            <p className="text-sm mb-4" style={{ color: "hsl(260 15% 50%)" }}>
              Форматы: .txt, .csv, .json<br/>
              <span className="text-xs">Примеры в корне проекта</span>
            </p>
            <input 
              type="file" 
              accept=".txt,.csv,.json" 
              onChange={(e) => setSelectedFile(e.target.files?.[0] || null)}
              className="w-full px-4 py-2.5 rounded-lg text-sm mb-4"
              style={{ background: "hsl(270 35% 7%)", color: "hsl(260 15% 55%)", border: "1px solid hsl(270 25% 20%)" }} 
            />
            <button 
              onClick={handleUpload}
              disabled={!selectedFile || uploading}
              className="w-full py-3 rounded-lg font-semibold text-white glow-btn disabled:opacity-50"
            >
              {uploading ? 'Загрузка...' : 'Загрузить'}
            </button>
          </div>
        </div>
      )}
    </div>
  );
};

export default ProxyPage;
