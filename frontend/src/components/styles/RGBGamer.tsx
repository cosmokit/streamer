import { sidebarItems, steps, progress, username } from "./mockData";

export const RGBGamer = () => (
  <div className="w-full h-full flex" style={{ background: "#0a0a0a", fontFamily: "'Segoe UI', sans-serif" }}>
    {/* Sidebar */}
    <div className="w-[28%] h-full p-3 flex flex-col gap-1" style={{
      background: "#111",
      borderRight: "1px solid transparent",
      borderImage: "linear-gradient(180deg, #ff0000, #ff8800, #ffff00, #00ff00, #0088ff, #8800ff) 1",
    }}>
      <div className="text-[10px] font-bold mb-2 px-2" style={{
        background: "linear-gradient(90deg, #ff0000, #ff8800, #00ff00, #0088ff, #8800ff)",
        WebkitBackgroundClip: "text",
        WebkitTextFillColor: "transparent",
      }}>
        🔥 RGB GAMER
      </div>
      {sidebarItems.map((item, i) => (
        <div
          key={i}
          className="flex items-center gap-1.5 px-2 py-1 rounded text-[8px]"
          style={{
            background: i === 0 ? "#ffffff0a" : "transparent",
            color: i === 0 ? "#fff" : "#555",
            borderLeft: i === 0 ? "2px solid #0088ff" : "2px solid transparent",
          }}
        >
          <span>{item.icon}</span>
          <span>{item.label}</span>
        </div>
      ))}
    </div>
    {/* Main */}
    <div className="flex-1 p-4 overflow-hidden">
      <div className="text-[9px] mb-1" style={{ color: "#555" }}>Привет, {username}</div>
      <div className="text-[13px] font-bold mb-3" style={{
        background: "linear-gradient(90deg, #ff0000, #ff8800, #ffff00, #00ff00, #0088ff)",
        WebkitBackgroundClip: "text",
        WebkitTextFillColor: "transparent",
      }}>
        Мой прогресс
      </div>
      <div className="w-full h-2 rounded-full mb-4" style={{ background: "#1a1a1a" }}>
        <div className="h-full rounded-full" style={{
          width: `${progress}%`,
          background: "linear-gradient(90deg, #ff0000, #ff8800, #ffff00, #00ff00, #0088ff, #8800ff)",
          boxShadow: "0 0 10px #0088ff44",
        }} />
      </div>
      <div className="flex flex-col gap-1.5">
        {steps.map((step, i) => {
          const colors = ["#ff0000", "#ff8800", "#ffff00", "#00ff00", "#0088ff", "#8800ff"];
          const c = colors[i % colors.length];
          return (
            <div
              key={i}
              className="flex items-center gap-2 px-2 py-1.5 rounded text-[8px]"
              style={{
                background: step.done ? "#ffffff06" : "#111",
                border: `1px solid ${step.done ? c + "44" : "#1a1a1a"}`,
                color: step.done ? "#ddd" : "#444",
              }}
            >
              <span style={{ color: step.done ? c : "#222" }}>{step.done ? "◆" : "◇"}</span>
              <span>{step.title}</span>
            </div>
          );
        })}
      </div>
    </div>
  </div>
);
