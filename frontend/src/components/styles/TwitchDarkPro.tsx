import { sidebarItems, steps, progress, username } from "./mockData";

export const TwitchDarkPro = () => (
  <div className="w-full h-full flex" style={{ background: "#0e0e10", fontFamily: "'Segoe UI', sans-serif" }}>
    {/* Sidebar */}
    <div className="w-[28%] h-full p-3 flex flex-col gap-1" style={{ background: "#1f1f23", borderRight: "1px solid #2f2f35" }}>
      <div className="text-[10px] font-bold mb-2 px-2" style={{ color: "#9147ff" }}>
        ⚡ STREAM<span style={{ color: "#bf94ff" }}>PRO</span>
      </div>
      {sidebarItems.map((item, i) => (
        <div
          key={i}
          className="flex items-center gap-1.5 px-2 py-1 rounded text-[8px]"
          style={{
            background: i === 0 ? "#9147ff22" : "transparent",
            color: i === 0 ? "#bf94ff" : "#adadb8",
          }}
        >
          <span>{item.icon}</span>
          <span>{item.label}</span>
        </div>
      ))}
    </div>
    {/* Main */}
    <div className="flex-1 p-4 overflow-hidden">
      <div className="text-[9px] mb-1" style={{ color: "#adadb8" }}>Привет, {username}</div>
      <div className="text-[13px] font-bold mb-3" style={{ color: "#efeff1" }}>
        Мой прогресс
      </div>
      <div className="w-full h-2 rounded mb-4" style={{ background: "#1f1f23" }}>
        <div className="h-full rounded" style={{ width: `${progress}%`, background: "#9147ff" }} />
      </div>
      <div className="flex flex-col gap-1.5">
        {steps.map((step, i) => (
          <div
            key={i}
            className="flex items-center gap-2 px-2 py-1.5 rounded text-[8px]"
            style={{
              background: step.done ? "#18181b" : "#1f1f23",
              color: step.done ? "#efeff1" : "#636369",
              border: `1px solid ${step.done ? "#2f2f35" : "#26262c"}`,
            }}
          >
            <span style={{ color: step.done ? "#9147ff" : "#3a3a40" }}>{step.done ? "✓" : "○"}</span>
            <span>{step.title}</span>
          </div>
        ))}
      </div>
    </div>
  </div>
);
